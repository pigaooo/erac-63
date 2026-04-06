<?php

namespace App\Observers;

use App\Models\Inscrito;
use App\Support\InscritoEmailDispatcher;

class InscritoObserver
{
    public function updated(Inscrito $inscrito): void
    {
        if (! $inscrito->wasChanged('is_paied')) {
            return;
        }

        if (! $inscrito->is_paied) {
            return;
        }

        app(InscritoEmailDispatcher::class)->dispatchPaymentConfirmation($inscrito);
    }
}
