<?php

namespace App\Jobs;

use App\Mail\BatchRegistrationConfirmationMail;
use App\Mail\IndividualRegistrationConfirmationMail;
use App\Models\Inscrito;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendRegistrationConfirmationEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $inscritoId,
        public bool $registeredByStore = false,
    ) {
    }

    public function handle(): void
    {
        $inscrito = Inscrito::query()
            ->with('loja')
            ->find($this->inscritoId);

        if (! $inscrito || $inscrito->registration_confirmation_sent_at !== null || blank($inscrito->email)) {
            return;
        }

        $mailable = $this->registeredByStore
            ? new BatchRegistrationConfirmationMail($inscrito)
            : new IndividualRegistrationConfirmationMail($inscrito);

        Mail::to($inscrito->email)->send($mailable);

        $inscrito->forceFill([
            'registration_confirmation_sent_at' => now(),
        ])->saveQuietly();
    }
}
