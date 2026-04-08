<?php

namespace App\Jobs;

use App\Mail\PaymentConfirmationMail;
use App\Models\Inscrito;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendPaymentConfirmationEmail implements ShouldQueue
{
    use Batchable;
    use Queueable;

    public function __construct(public string $inscritoId)
    {
    }

    public function handle(): void
    {
        $inscrito = Inscrito::query()
            ->with('loja')
            ->find($this->inscritoId);

        if (! $inscrito || ! $inscrito->is_paied || $inscrito->payment_confirmation_sent_at !== null || blank($inscrito->email)) {
            return;
        }

        Mail::to($inscrito->email)->send(new PaymentConfirmationMail($inscrito));

        $inscrito->forceFill([
            'payment_confirmation_sent_at' => now(),
        ])->saveQuietly();
    }
}
