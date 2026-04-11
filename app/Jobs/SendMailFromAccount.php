<?php

namespace App\Jobs;

use App\Models\MailAccount;
use App\Support\Mail\MailAccountManager;
use App\Support\Mail\MailEventRecorder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SendMailFromAccount implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<int, array{address:string,name:?string}>  $to
     * @param  array<int, array{address:string,name:?string}>  $cc
     * @param  array<int, array{address:string,name:?string}>  $bcc
     * @param  array<int, array{address:string,name:?string}>  $replyTo
     * @param  array<int, array{path:string,name:?string}>  $attachments
     * @param  array<int, string>  $references
     */
    public function __construct(
        public readonly int $mailAccountId,
        public readonly array $to,
        public readonly string $subject,
        public readonly ?string $htmlBody = null,
        public readonly ?string $textBody = null,
        public readonly array $cc = [],
        public readonly array $bcc = [],
        public readonly array $replyTo = [],
        public readonly array $attachments = [],
        public readonly ?string $inReplyTo = null,
        public readonly array $references = [],
    ) {}

    public function handle(
        MailAccountManager $mailAccountManager,
        MailEventRecorder $eventRecorder,
    ): void {
        $account = MailAccount::query()->find($this->mailAccountId);

        if ($account === null || ! $account->is_active) {
            return;
        }

        $attachments = array_values(array_filter(array_map(function (array $attachment): ?array {
            $path = $attachment['path'] ?? null;

            if (! is_string($path) || ! Storage::disk('local')->exists($path)) {
                return null;
            }

            return [
                'path' => Storage::disk('local')->path($path),
                'name' => $attachment['name'] ?? basename($path),
            ];
        }, $this->attachments)));

        try {
            $mailAccountManager->send(
                $account,
                $this->to,
                $this->subject,
                $this->htmlBody,
                $this->textBody,
                $this->cc,
                $this->bcc,
                $this->replyTo,
                $attachments,
                $this->inReplyTo,
                $this->references,
            );

            $eventRecorder->record($account, 'sent', 'Email enviado com sucesso.', payload: [
                'subject' => $this->subject,
                'to' => $this->to,
            ]);

            SyncSentFolder::dispatch($account->getKey());
        } catch (Throwable $exception) {
            $account->forceFill([
                'last_error_at' => now(),
                'last_error_message' => $exception->getMessage(),
            ])->save();

            $eventRecorder->record($account, 'send_failed', 'Falha ao enviar email.', payload: [
                'subject' => $this->subject,
                'message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
