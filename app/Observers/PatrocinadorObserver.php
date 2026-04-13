<?php

namespace App\Observers;

use App\Models\Patrocinador;
use Illuminate\Support\Facades\Cache;

class PatrocinadorObserver
{
    public function saved(Patrocinador $patrocinador): void
    {
        $this->forgetPublicCache();
    }

    public function deleted(Patrocinador $patrocinador): void
    {
        $this->forgetPublicCache();
    }

    protected function forgetPublicCache(): void
    {
        Cache::forget('site.patrocinadores');
    }
}
