<?php

namespace App\Support\Mail;

use App\Models\MailAccount;
use App\Support\Mail\Contracts\ImapClient;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;
use Webklex\PHPIMAP\Address;
use Webklex\PHPIMAP\Attachment;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\Config;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\IMAP;
use Webklex\PHPIMAP\Message;

class WebklexImapClient implements ImapClient
{
    public function testConnection(MailAccount $account): void
    {
        $client = $this->connect($account);

        try {
            $client->getFolderByPath($account->inbox_folder_name ?: 'INBOX');
        } finally {
            $client->disconnect();
        }
    }

    public function listFolders(MailAccount $account): array
    {
        $client = $this->connect($account);

        try {
            $folders = $client->getFolders(hierarchical: true);
            $payload = [];

            foreach ($folders as $folder) {
                $this->appendFolderPayload($payload, $folder);
            }

            return $payload;
        } finally {
            $client->disconnect();
        }
    }

    public function fetchFolderMessages(MailAccount $account, string $remoteFolder): array
    {
        $client = $this->connect($account);

        try {
            $folder = $this->getFolder($client, $remoteFolder);

            $messages = $folder
                ->messages()
                ->all()
                ->setSequence(IMAP::ST_UID)
                ->leaveUnread()
                ->setFetchBody(false)
                ->setFetchFlags(true)
                ->get();

            return $messages
                ->map(fn (Message $message): array => $this->mapMessageSummary($message))
                ->all();
        } finally {
            $client->disconnect();
        }
    }

    public function fetchMessage(MailAccount $account, string $remoteFolder, int $uid): array
    {
        $client = $this->connect($account);

        try {
            $folder = $this->getFolder($client, $remoteFolder);
            $message = $folder
                ->messages()
                ->setSequence(IMAP::ST_UID)
                ->leaveUnread()
                ->setFetchBody(true)
                ->setFetchFlags(true)
                ->getMessageByUid($uid);

            return [
                'text_body' => $this->nullIfBlank($message->getTextBody()),
                'html_body' => $this->nullIfBlank($message->getHTMLBody()),
                'attachments' => $message->getAttachments()
                    ->map(fn (Attachment $attachment): array => $this->mapAttachment($attachment))
                    ->values()
                    ->all(),
                'has_attachments' => $message->hasAttachments(),
            ];
        } finally {
            $client->disconnect();
        }
    }

    public function setMessageSeen(MailAccount $account, string $remoteFolder, int $uid, bool $seen): void
    {
        $client = $this->connect($account);

        try {
            $folder = $this->getFolder($client, $remoteFolder);
            $message = $folder
                ->messages()
                ->setSequence(IMAP::ST_UID)
                ->leaveUnread()
                ->setFetchBody(false)
                ->setFetchFlags(true)
                ->getMessageByUid($uid);

            $status = $seen
                ? $message->setFlag('Seen')
                : $message->unsetFlag('Seen');

            if (! $status) {
                throw new RuntimeException('Nao foi possivel atualizar o status de leitura da mensagem.');
            }
        } finally {
            $client->disconnect();
        }
    }

    public function moveMessage(MailAccount $account, string $fromRemoteFolder, int $uid, string $toRemoteFolder): void
    {
        $client = $this->connect($account);

        try {
            $folder = $this->getFolder($client, $fromRemoteFolder);
            $message = $folder
                ->messages()
                ->setSequence(IMAP::ST_UID)
                ->leaveUnread()
                ->setFetchBody(false)
                ->setFetchFlags(true)
                ->getMessageByUid($uid);

            $movedMessage = $message->move($toRemoteFolder, expunge: true);

            if ($movedMessage === null) {
                throw new RuntimeException('Nao foi possivel mover a mensagem no servidor remoto.');
            }
        } finally {
            $client->disconnect();
        }
    }

    private function connect(MailAccount $account): Client
    {
        try {
            $client = new Client(Config::make([
                'default' => 'runtime',
                'accounts' => [
                    'runtime' => [
                        'host' => $account->imap_host,
                        'port' => $account->imap_port,
                        'protocol' => 'imap',
                        'encryption' => $this->normalizeEncryption($account->imap_encryption),
                        'validate_cert' => (bool) $account->imap_validate_cert,
                        'username' => $account->imap_username,
                        'password' => $account->imap_password,
                        'authentication' => null,
                        'timeout' => 30,
                    ],
                ],
                'options' => [
                    'fetch' => IMAP::FT_PEEK,
                    'fetch_order' => 'desc',
                    'sequence' => IMAP::ST_UID,
                    'delimiter' => '/',
                    'soft_fail' => false,
                ],
            ])->getClientConfig('runtime'));

            $client->connect();

            return $client;
        } catch (Throwable $exception) {
            throw new RuntimeException($this->formatErrorMessage($exception), previous: $exception);
        }
    }

    private function getFolder(Client $client, string $remoteFolder): Folder
    {
        $folder = $client->getFolderByPath($remoteFolder, soft_fail: false);

        if ($folder === null) {
            throw new RuntimeException('A pasta remota informada nao foi encontrada.');
        }

        return $folder;
    }

    /**
     * @param  array<int, array<string, mixed>>  $payload
     */
    private function appendFolderPayload(array &$payload, Folder $folder): void
    {
        $attributes = [
            'no_inferiors' => (bool) $folder->no_inferiors,
            'no_select' => (bool) $folder->no_select,
            'marked' => (bool) $folder->marked,
            'has_children' => (bool) $folder->has_children,
            'referral' => (bool) $folder->referral,
        ];

        $payload[] = [
            'remote_name' => $folder->path,
            'display_name' => $folder->full_name ?: $folder->name,
            'delimiter' => $folder->delimiter ?: '/',
            'attributes' => $attributes,
            'special_use' => $this->detectSpecialUse($folder->full_name ?: $folder->name),
            'uid_validity' => null,
            'remote_hash' => sha1($folder->path . '|' . json_encode($attributes)),
            'is_selectable' => ! $folder->no_select,
        ];

        foreach ($folder->children as $child) {
            $this->appendFolderPayload($payload, $child);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function mapMessageSummary(Message $message): array
    {
        $receivedAt = $this->parseDate($message->getDate());
        $textBody = $this->nullIfBlank($message->getTextBody());
        $htmlBody = $this->nullIfBlank($message->getHTMLBody());

        return [
            'uid' => (int) $message->getUid(),
            'remote_message_id' => $this->attributeToString($message->getMessageId()),
            'subject' => $this->attributeToString($message->getSubject()),
            'from_addresses' => $this->mapAddresses($message->from ?? null),
            'to_addresses' => $this->mapAddresses($message->to ?? null),
            'cc_addresses' => $this->mapAddresses($message->cc ?? null),
            'bcc_addresses' => $this->mapAddresses($message->bcc ?? null),
            'reply_to_addresses' => $this->mapAddresses($message->reply_to ?? null),
            'headers' => [
                'references' => $this->normalizeAttributeArray($message->getReferences()),
                'in_reply_to' => $this->normalizeAttributeArray($message->in_reply_to ?? null),
            ],
            'snippet' => $this->buildSnippet($textBody, $htmlBody),
            'received_at' => $receivedAt,
            'sent_at' => $receivedAt,
            'has_attachments' => $message->hasAttachments(),
            'is_seen' => $message->hasFlag('seen'),
            'is_answered' => $message->hasFlag('answered'),
            'is_flagged' => $message->hasFlag('flagged'),
            'is_draft' => $message->hasFlag('draft'),
            'is_deleted' => $message->hasFlag('deleted'),
            'last_remote_update_at' => $receivedAt ?? now(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapAttachment(Attachment $attachment): array
    {
        $filename = $attachment->getFilename() ?: $attachment->getName();

        return [
            'part_number' => (string) $attachment->getPartNumber(),
            'filename' => $filename,
            'content_type' => $attachment->getContentType(),
            'size' => $attachment->getSize(),
            'content_id' => Str::of((string) $attachment->getId())->trim('<>')->toString() ?: null,
            'is_inline' => strcasecmp((string) $attachment->getDisposition(), 'inline') === 0,
            'content' => $attachment->getContent(),
        ];
    }

    /**
     * @return array<int, array{address:string,name:?string}>
     */
    private function mapAddresses(mixed $attribute): array
    {
        if ($attribute === null) {
            return [];
        }

        $values = method_exists($attribute, 'toArray')
            ? $attribute->toArray()
            : (is_array($attribute) ? $attribute : [$attribute]);

        return array_values(array_filter(array_map(function (mixed $address): ?array {
            if (! $address instanceof Address && ! is_object($address)) {
                return null;
            }

            $email = $address->mail ?? null;

            if (blank($email)) {
                $mailbox = $address->mailbox ?? null;
                $host = $address->host ?? null;
                $email = trim(implode('@', array_filter([$mailbox, $host])), '@');
            }

            if (blank($email)) {
                return null;
            }

            $name = $address->personal ?? null;

            return [
                'address' => (string) $email,
                'name' => filled($name) ? (string) $name : null,
            ];
        }, $values)));
    }

    private function parseDate(mixed $attribute): ?Carbon
    {
        if ($attribute === null) {
            return null;
        }

        if (method_exists($attribute, 'toDate')) {
            return Carbon::instance($attribute->toDate());
        }

        $value = $this->attributeToString($attribute);

        return filled($value) ? Carbon::parse($value) : null;
    }

    /**
     * @return array<int, string>
     */
    private function normalizeAttributeArray(mixed $attribute): array
    {
        if ($attribute === null) {
            return [];
        }

        $values = method_exists($attribute, 'toArray')
            ? $attribute->toArray()
            : (is_array($attribute) ? $attribute : [$attribute]);

        return array_values(array_filter(array_map(
            fn (mixed $value): ?string => filled($value) ? (string) $value : null,
            $values,
        )));
    }

    private function attributeToString(mixed $attribute): ?string
    {
        if ($attribute === null) {
            return null;
        }

        $value = is_string($attribute)
            ? $attribute
            : (method_exists($attribute, 'toString') ? $attribute->toString() : (string) $attribute);

        return $this->nullIfBlank($value);
    }

    private function buildSnippet(?string $textBody, ?string $htmlBody): ?string
    {
        $source = $textBody ?: ($htmlBody ? strip_tags($htmlBody) : null);

        if (blank($source)) {
            return null;
        }

        return Str::limit(trim(preg_replace('/\s+/', ' ', $source) ?: ''), 180);
    }

    private function detectSpecialUse(string $displayName): ?string
    {
        $normalized = mb_strtolower($displayName);

        return match (true) {
            str_contains($normalized, 'inbox') || str_contains($normalized, 'entrada') => 'inbox',
            str_contains($normalized, 'sent') || str_contains($normalized, 'enviad') || str_contains($normalized, 'saida') => 'sent',
            str_contains($normalized, 'draft') || str_contains($normalized, 'rascunh') => 'drafts',
            str_contains($normalized, 'spam') || str_contains($normalized, 'junk') => 'spam',
            str_contains($normalized, 'trash') || str_contains($normalized, 'lixeira') || str_contains($normalized, 'deleted') => 'trash',
            default => null,
        };
    }

    private function normalizeEncryption(?string $encryption): string
    {
        return match (strtolower((string) $encryption)) {
            '', 'none', 'notls' => 'none',
            'starttls' => 'starttls',
            'tls' => 'tls',
            default => 'ssl',
        };
    }

    private function formatErrorMessage(Throwable $exception): string
    {
        $messages = [];
        $current = $exception;

        while ($current !== null) {
            $message = trim($current->getMessage());

            if ($message !== '' && ! in_array($message, $messages, true)) {
                $messages[] = $message;
            }

            $current = $current->getPrevious();
        }

        if ($messages === []) {
            return 'Falha ao conectar com o servidor IMAP.';
        }

        return implode(' | ', $messages);
    }

    private function nullIfBlank(?string $value): ?string
    {
        return filled($value) ? $value : null;
    }
}
