<?php

namespace App\Support\Mail;

use App\Models\MailAccount;
use App\Models\MailEvent;
use App\Models\MailMessage;

class MailEventRecorder
{
    public function record(
        MailAccount $account,
        string $type,
        string $summary,
        ?MailMessage $message = null,
        array $payload = [],
    ): MailEvent {
        return MailEvent::query()->create([
            'mail_account_id' => $account->getKey(),
            'mail_message_id' => $message?->getKey(),
            'type' => $type,
            'summary' => $summary,
            'payload' => $payload,
            'occurred_at' => now(),
        ]);
    }
}
