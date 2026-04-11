<?php

namespace Tests\Unit;

use App\Models\MailAccount;
use App\Models\MailFolder;
use App\Models\MailMessage;
use App\Support\Mail\Contracts\ImapClient;
use App\Support\Mail\ImapSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Fakes\FakeImapClient;
use Tests\TestCase;

class ImapSyncServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_folders_cria_atualiza_e_desativa_pastas(): void
    {
        $account = MailAccount::query()->create($this->accountData());

        $existingFolder = MailFolder::query()->create([
            'mail_account_id' => $account->id,
            'remote_name' => 'Old',
            'display_name' => 'Old',
            'is_active' => true,
            'is_selectable' => true,
        ]);

        $fake = new FakeImapClient();
        $fake->folders = [
            [
                'remote_name' => 'INBOX',
                'display_name' => 'Inbox',
                'delimiter' => '/',
                'attributes' => [],
                'special_use' => 'inbox',
                'uid_validity' => '1',
                'remote_hash' => 'hash-inbox',
                'is_selectable' => true,
            ],
        ];

        $this->app->instance(ImapClient::class, $fake);

        $service = $this->app->make(ImapSyncService::class);
        $service->syncFolders($account);

        $this->assertDatabaseHas('mail_folders', [
            'mail_account_id' => $account->id,
            'remote_name' => 'INBOX',
            'display_name' => 'Inbox',
            'is_active' => true,
        ]);

        $existingFolder->refresh();
        $this->assertFalse($existingFolder->is_active);
    }

    public function test_sync_folder_messages_cria_evento_e_marca_exclusoes(): void
    {
        $account = MailAccount::query()->create($this->accountData());
        $folder = MailFolder::query()->create([
            'mail_account_id' => $account->id,
            'remote_name' => 'INBOX',
            'display_name' => 'Inbox',
            'special_use' => 'inbox',
            'is_active' => true,
            'is_selectable' => true,
        ]);

        $deleted = MailMessage::query()->create([
            'mail_account_id' => $account->id,
            'mail_folder_id' => $folder->id,
            'uid' => 99,
            'subject' => 'Old',
            'direction' => 'inbound',
        ]);

        $fake = new FakeImapClient();
        $fake->messagesByFolder['INBOX'] = [
            [
                'uid' => 10,
                'remote_message_id' => '<message-10@example.com>',
                'subject' => 'Novo email',
                'from_addresses' => [['address' => 'remetente@example.com', 'name' => 'Remetente']],
                'to_addresses' => [['address' => 'destino@example.com', 'name' => null]],
                'cc_addresses' => [],
                'bcc_addresses' => [],
                'reply_to_addresses' => [],
                'headers' => [],
                'snippet' => null,
                'received_at' => now(),
                'sent_at' => now(),
                'has_attachments' => false,
                'is_seen' => false,
                'is_answered' => false,
                'is_flagged' => false,
                'is_draft' => false,
                'is_deleted' => false,
                'last_remote_update_at' => now(),
            ],
        ];

        $this->app->instance(ImapClient::class, $fake);

        $service = $this->app->make(ImapSyncService::class);
        $service->syncFolderMessages($folder);

        $this->assertDatabaseHas('mail_messages', [
            'mail_account_id' => $account->id,
            'mail_folder_id' => $folder->id,
            'uid' => 10,
            'subject' => 'Novo email',
        ]);

        $deleted->refresh();
        $this->assertTrue($deleted->is_deleted);

        $this->assertDatabaseHas('mail_events', [
            'mail_account_id' => $account->id,
            'type' => 'received',
        ]);
    }

    private function accountData(): array
    {
        return [
            'name' => 'Conta teste',
            'email_address' => 'conta@example.com',
            'from_name' => 'Conta Teste',
            'imap_host' => 'imap.example.com',
            'imap_port' => 993,
            'imap_encryption' => 'ssl',
            'imap_username' => 'imap-user',
            'imap_password' => 'imap-secret',
            'imap_validate_cert' => true,
            'smtp_host' => 'smtp.example.com',
            'smtp_port' => 465,
            'smtp_encryption' => 'ssl',
            'smtp_username' => 'smtp-user',
            'smtp_password' => 'smtp-secret',
            'inbox_folder_name' => 'INBOX',
            'sent_folder_name' => 'Sent',
            'is_active' => true,
            'sync_interval_minutes' => 5,
        ];
    }
}
