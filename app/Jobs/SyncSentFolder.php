<?php

namespace App\Jobs;

use App\Models\MailAccount;
use App\Support\Mail\ImapSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncSentFolder implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $mailAccountId,
    ) {}

    public function handle(ImapSyncService $syncService): void
    {
        $account = MailAccount::query()->find($this->mailAccountId);

        if ($account === null || ! $account->is_active) {
            return;
        }

        $syncService->runSafe(
            fn () => $syncService->syncSentFolder($account),
            $account,
            'sync_failed',
            'Falha ao sincronizar a pasta de enviados da conta ' . $account->name . '.',
        );
    }
}
