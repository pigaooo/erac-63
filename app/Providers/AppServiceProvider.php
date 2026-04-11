<?php

namespace App\Providers;

use App\Models\Inscrito;
use App\Observers\InscritoObserver;
use App\Support\Mail\Contracts\ImapClient;
use App\Support\Mail\WebklexImapClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ImapClient::class, WebklexImapClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inscrito::observe(InscritoObserver::class);
    }
}
