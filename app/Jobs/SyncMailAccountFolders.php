<?php

namespace App\Jobs;

use App\Models\MailAccount;
use App\Support\Mail\ImapSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncMailAccountFolders implements ShouldQueue
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
            function () use ($syncService, $account): void {
                $folders = $syncService->syncFolders($account);

                $folders
                    ->filter(fn ($folder) => $folder->is_active)
                    ->each(fn ($folder) => SyncMailFolderMessages::dispatch($folder->getKey()));
            },
            $account,
            'sync_failed',
            'Falha ao sincronizar pastas da conta ' . $account->name . '.',
        );
    }
}
