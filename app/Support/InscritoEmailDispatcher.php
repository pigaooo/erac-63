<?php

namespace App\Support;

use App\Jobs\SendPaymentConfirmationEmail;
use App\Jobs\SendRegistrationConfirmationEmail;
use App\Models\Inscrito;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class InscritoEmailDispatcher
{
    public function dispatchRegistrationConfirmation(Inscrito $inscrito, bool $registeredByStore = false): void
    {
        if ($inscrito->registration_confirmation_sent_at !== null) {
            return;
        }

        DB::afterCommit(function () use ($inscrito, $registeredByStore): void {
            SendRegistrationConfirmationEmail::dispatch($inscrito->getKey(), $registeredByStore);
        });
    }

    public function dispatchRegistrationConfirmations(Collection $inscritos, bool $registeredByStore = false): void
    {
        $inscritos
            ->filter(fn (Inscrito $inscrito) => $inscrito->registration_confirmation_sent_at === null)
            ->each(fn (Inscrito $inscrito) => $this->dispatchRegistrationConfirmation($inscrito, $registeredByStore));
    }

    public function dispatchPaymentConfirmation(Inscrito $inscrito): void
    {
        if (! $inscrito->is_paied || $inscrito->payment_confirmation_sent_at !== null) {
            return;
        }

        DB::afterCommit(function () use ($inscrito): void {
            SendPaymentConfirmationEmail::dispatch($inscrito->getKey());
        });
    }

    public function dispatchPaymentBatch(Collection $inscritos): void
    {
        $jobs = $inscritos
            ->filter(fn (Inscrito $inscrito) => $inscrito->is_paied && $inscrito->payment_confirmation_sent_at === null)
            ->map(fn (Inscrito $inscrito) => new SendPaymentConfirmationEmail($inscrito->getKey()))
            ->values()
            ->all();

        if ($jobs === []) {
            return;
        }

        DB::afterCommit(function () use ($jobs): void {
            Bus::batch($jobs)
                ->name('inscrito-payment-confirmations')
                ->dispatch();
        });
    }
}
