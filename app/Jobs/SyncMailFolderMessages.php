<?php

namespace App\Jobs;

use App\Models\MailFolder;
use App\Support\Mail\ImapSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncMailFolderMessages implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $mailFolderId,
    ) {}

    public function handle(ImapSyncService $syncService): void
    {
        $folder = MailFolder::query()->with('account')->find($this->mailFolderId);

        if ($folder === null || ! $folder->is_active || ! $folder->account->is_active) {
            return;
        }

        $syncService->runSafe(
            fn () => $syncService->syncFolderMessages($folder),
            $folder->account,
            'sync_failed',
            'Falha ao sincronizar mensagens da pasta ' . $folder->display_name . '.',
        );
    }
}
