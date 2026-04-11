<?php

namespace App\Console\Commands;

use App\Jobs\SyncMailAccountFolders;
use App\Models\MailAccount;
use Illuminate\Console\Command;

class SyncMailAccounts extends Command
{
    protected $signature = 'mail:sync-accounts';

    protected $description = 'Dispatch folder and message synchronization jobs for active mail accounts';

    public function handle(): int
    {
        $accounts = MailAccount::query()
            ->where('is_active', true)
            ->get();

        foreach ($accounts as $account) {
            SyncMailAccountFolders::dispatch($account->getKey());
        }

        $this->info('Mail synchronization jobs dispatched for ' . $accounts->count() . ' account(s).');

        return self::SUCCESS;
    }
}
