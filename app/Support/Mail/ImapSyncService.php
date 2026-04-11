<?php

namespace App\Support\Mail;

use App\Models\MailAccount;
use App\Models\MailAttachment;
use App\Models\MailFolder;
use App\Models\MailMessage;
use App\Support\Mail\Contracts\ImapClient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ImapSyncService
{
    public function __construct(
        private readonly ImapClient $imapClient,
        private readonly MailEventRecorder $eventRecorder,
    ) {}

    public function testConnection(MailAccount $account): void
    {
        $this->imapClient->testConnection($account);
    }

    /**
     * @return Collection<int, MailFolder>
     */
    public function syncFolders(MailAccount $account): Collection
    {
        $existingNames = $account->folders()->pluck('remote_name')->all();
        $remoteFolders = collect($this->imapClient->listFolders($account));
        $remoteNames = $remoteFolders->pluck('remote_name')->all();

        $synced = DB::transaction(function () use ($account, $remoteFolders, $remoteNames): Collection {
            $collection = $remoteFolders->map(function (array $folderData) use ($account): MailFolder {
                $folder = MailFolder::query()->updateOrCreate(
                    [
                        'mail_account_id' => $account->getKey(),
                        'remote_name' => $folderData['remote_name'],
                    ],
                    [
                        'display_name' => $folderData['display_name'],
                        'delimiter' => $folderData['delimiter'],
                        'attributes' => $folderData['attributes'],
                        'special_use' => $this->resolveSpecialUse($account, $folderData),
                        'uid_validity' => $folderData['uid_validity'],
                        'remote_hash' => $folderData['remote_hash'],
                        'is_active' => true,
                        'is_selectable' => (bool) ($folderData['is_selectable'] ?? true),
                        'last_seen_at' => now(),
                        'last_synced_at' => now(),
                    ],
                );

                return $folder;
            });

            MailFolder::query()
                ->where('mail_account_id', $account->getKey())
                ->when($remoteNames !== [], fn ($query) => $query->whereNotIn('remote_name', $remoteNames))
                ->update([
                    'is_active' => false,
                    'last_seen_at' => now(),
                ]);

            return $collection;
        });

        $account->forceFill([
            'last_synced_at' => now(),
            'last_error_at' => null,
            'last_error_message' => null,
        ])->save();

        foreach (array_diff($remoteNames, $existingNames) as $createdFolder) {
            $this->eventRecorder->record(
                $account,
                'folder_created',
                'Pasta sincronizada do servidor: ' . $createdFolder . '.',
                payload: ['folder' => $createdFolder],
            );
        }

        foreach (array_diff($existingNames, $remoteNames) as $removedFolder) {
            $this->eventRecorder->record(
                $account,
                'folder_removed',
                'Pasta removida ou indisponivel no servidor: ' . $removedFolder . '.',
                payload: ['folder' => $removedFolder],
            );
        }

        return $synced;
    }

    /**
     * @return Collection<int, MailMessage>
     */
    public function syncFolderMessages(MailFolder $folder): Collection
    {
        $folder->loadMissing('account');
        $remoteMessages = collect($this->imapClient->fetchFolderMessages($folder->account, $folder->remote_name));
        $remoteUids = $remoteMessages->pluck('uid')->all();

        $messages = DB::transaction(function () use ($folder, $remoteMessages, $remoteUids): Collection {
            $collection = $remoteMessages->map(function (array $messageData) use ($folder): MailMessage {
                $existing = MailMessage::query()
                    ->where('mail_account_id', $folder->mail_account_id)
                    ->where('mail_folder_id', $folder->getKey())
                    ->where('uid', $messageData['uid'])
                    ->first();

                $message = MailMessage::query()->updateOrCreate(
                    [
                        'mail_account_id' => $folder->mail_account_id,
                        'mail_folder_id' => $folder->getKey(),
                        'uid' => $messageData['uid'],
                    ],
                    [
                        'remote_message_id' => $messageData['remote_message_id'],
                        'subject' => $messageData['subject'],
                        'from_addresses' => $messageData['from_addresses'],
                        'to_addresses' => $messageData['to_addresses'],
                        'cc_addresses' => $messageData['cc_addresses'],
                        'bcc_addresses' => $messageData['bcc_addresses'],
                        'reply_to_addresses' => $messageData['reply_to_addresses'],
                        'headers' => $messageData['headers'],
                        'snippet' => $messageData['snippet'],
                        'received_at' => $messageData['received_at'],
                        'sent_at' => $messageData['sent_at'],
                        'has_attachments' => (bool) $messageData['has_attachments'],
                        'is_seen' => (bool) $messageData['is_seen'],
                        'is_answered' => (bool) $messageData['is_answered'],
                        'is_flagged' => (bool) $messageData['is_flagged'],
                        'is_draft' => (bool) $messageData['is_draft'],
                        'is_deleted' => (bool) $messageData['is_deleted'],
                        'direction' => $this->resolveDirection($folder, $messageData),
                        'sync_status' => 'synced',
                        'last_remote_update_at' => $messageData['last_remote_update_at'] ?? now(),
                        'synced_at' => now(),
                    ],
                );

                if ($existing === null && $message->direction === 'inbound') {
                    $this->eventRecorder->record(
                        $folder->account,
                        'received',
                        'Novo email recebido em ' . $folder->display_name . '.',
                        $message,
                    );
                }

                return $message;
            });

            $query = MailMessage::query()
                ->where('mail_account_id', $folder->mail_account_id)
                ->where('mail_folder_id', $folder->getKey());

            if ($remoteUids !== []) {
                $query->whereNotIn('uid', $remoteUids);
            }

            $query->update([
                'is_deleted' => true,
                'sync_status' => 'deleted',
                'synced_at' => now(),
            ]);

            $folder->forceFill([
                'last_synced_at' => now(),
                'last_seen_at' => now(),
            ])->save();

            return $collection;
        });

        $folder->account->forceFill([
            'last_synced_at' => now(),
            'last_error_at' => null,
            'last_error_message' => null,
        ])->save();

        return $messages;
    }

    public function hydrateMessage(MailMessage $message): MailMessage
    {
        $message->loadMissing(['account', 'folder', 'attachments']);
        $payload = $this->imapClient->fetchMessage($message->account, $message->folder->remote_name, $message->uid);

        DB::transaction(function () use ($message, $payload): void {
            $message->forceFill([
                'text_body' => $payload['text_body'],
                'html_body' => $payload['html_body'],
                'has_attachments' => (bool) ($payload['has_attachments'] ?? false),
                'synced_at' => now(),
            ])->save();

            foreach ($payload['attachments'] ?? [] as $attachment) {
                $path = $this->storeAttachment($message, $attachment);

                MailAttachment::query()->updateOrCreate(
                    [
                        'mail_message_id' => $message->getKey(),
                        'part_number' => $attachment['part_number'],
                    ],
                    [
                        'filename' => $attachment['filename'],
                        'content_type' => $attachment['content_type'],
                        'size' => $attachment['size'],
                        'content_id' => $attachment['content_id'],
                        'path' => $path,
                        'is_inline' => (bool) $attachment['is_inline'],
                        'is_downloaded' => $path !== null,
                    ],
                );
            }
        });

        return $message->fresh(['attachments', 'folder', 'account']);
    }

    public function markMessageSeen(MailMessage $message, bool $seen): MailMessage
    {
        $message->loadMissing(['account', 'folder']);
        $this->imapClient->setMessageSeen($message->account, $message->folder->remote_name, $message->uid, $seen);

        $message->forceFill([
            'is_seen' => $seen,
            'synced_at' => now(),
        ])->save();

        return $message->refresh();
    }

    public function moveMessage(MailMessage $message, MailFolder $destination): void
    {
        $message->loadMissing(['account', 'folder']);

        $this->imapClient->moveMessage(
            $message->account,
            $message->folder->remote_name,
            $message->uid,
            $destination->remote_name,
        );

        $message->forceFill([
            'is_deleted' => true,
            'sync_status' => 'moved',
            'synced_at' => now(),
        ])->save();

        $this->syncFolderMessages($destination);
        $this->syncFolderMessages($message->folder);
    }

    public function deleteMessagePermanently(MailMessage $message): void
    {
        $message->loadMissing(['account', 'folder']);

        $this->imapClient->deleteMessage(
            $message->account,
            $message->folder->remote_name,
            $message->uid,
        );

        $sourceFolder = $message->folder;

        DB::transaction(function () use ($message): void {
            $message->attachments()->delete();
            $message->events()->delete();
            $message->delete();
        });

        $this->syncFolderMessages($sourceFolder);
    }

    public function syncSentFolder(MailAccount $account): void
    {
        $folderName = $account->sent_folder_name;

        if (blank($folderName)) {
            return;
        }

        $folder = MailFolder::query()
            ->where('mail_account_id', $account->getKey())
            ->where('remote_name', $folderName)
            ->first();

        if ($folder === null) {
            $folder = $this->syncFolders($account)
                ->firstWhere('remote_name', $folderName);
        }

        if ($folder !== null) {
            $this->syncFolderMessages($folder);
        }
    }

    public function runSafe(callable $callback, MailAccount $account, string $errorType, string $summary): mixed
    {
        try {
            return $callback();
        } catch (Throwable $exception) {
            $account->forceFill([
                'last_error_at' => now(),
                'last_error_message' => Str::limit($exception->getMessage(), 500),
            ])->save();

            $this->eventRecorder->record($account, $errorType, $summary, payload: [
                'message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    private function resolveSpecialUse(MailAccount $account, array $folderData): ?string
    {
        $remoteName = $folderData['remote_name'];

        return match ($remoteName) {
            $account->inbox_folder_name => 'inbox',
            $account->sent_folder_name => 'sent',
            $account->drafts_folder_name => 'drafts',
            $account->spam_folder_name => 'spam',
            $account->trash_folder_name => 'trash',
            default => $folderData['special_use'] ?? null,
        };
    }

    private function resolveDirection(MailFolder $folder, array $messageData): string
    {
        return $folder->special_use === 'sent'
            ? 'outbound'
            : ($messageData['direction'] ?? 'inbound');
    }

    /**
     * @param  array<string, mixed>  $attachment
     */
    private function storeAttachment(MailMessage $message, array $attachment): ?string
    {
        if (! array_key_exists('content', $attachment)) {
            return null;
        }

        $filename = $attachment['filename'] ?: ('attachment-' . $attachment['part_number']);
        $filename = preg_replace('/[^A-Za-z0-9._-]/', '-', $filename) ?: 'attachment';
        $path = sprintf(
            'private/mail/accounts/%d/messages/%d/%s-%s',
            $message->mail_account_id,
            $message->getKey(),
            $attachment['part_number'],
            $filename,
        );

        Storage::disk('local')->put($path, $attachment['content']);

        return $path;
    }
}
