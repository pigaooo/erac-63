<?php

namespace Tests\Feature;

use App\Filament\Resources\MailAccountResource\Pages\CreateMailAccount;
use App\Filament\Resources\MailAccountResource\Pages\EditMailAccount;
use App\Models\MailAccount;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithSettingsFile;
use Tests\TestCase;

class MailAccountResourceTest extends TestCase
{
    use InteractsWithSettingsFile;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useTemporarySettingsFile('mail-account-resource-test.json');
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    protected function tearDown(): void
    {
        $this->tearDownSettingsFile();
        Filament::setCurrentPanel(null);

        parent::tearDown();
    }

    public function test_cria_conta_de_email_com_credenciais_criptografadas(): void
    {
        $admin = $this->authenticateAdmin();

        $this->actingAs($admin);

        Livewire::test(CreateMailAccount::class)
            ->fillForm([
                'name' => 'Suporte',
                'email_address' => 'suporte@example.com',
                'from_name' => 'Suporte',
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
                'smtp_ehlo_domain' => 'example.com',
                'inbox_folder_name' => 'INBOX',
                'sent_folder_name' => 'Sent',
                'is_active' => true,
                'sync_interval_minutes' => 5,
            ])
            ->call('create')
            ->assertNotified();

        $account = MailAccount::query()->where('email_address', 'suporte@example.com')->first();

        $this->assertNotNull($account);
        $this->assertNotSame('imap-secret', $account->getRawOriginal('imap_password'));
        $this->assertNotSame('smtp-secret', $account->getRawOriginal('smtp_password'));
        $this->assertSame('example.com', $account->smtp_ehlo_domain);
    }

    public function test_edita_senha_imap_sem_perder_o_valor(): void
    {
        $admin = $this->authenticateAdmin();

        $this->actingAs($admin);

        $account = MailAccount::query()->create([
            'name' => 'Suporte',
            'email_address' => 'suporte@example.com',
            'from_name' => 'Suporte',
            'imap_host' => 'imap.example.com',
            'imap_port' => 993,
            'imap_encryption' => 'ssl',
            'imap_username' => 'imap-user',
            'imap_password' => 'senha-antiga',
            'imap_validate_cert' => true,
            'smtp_host' => 'imap.example.com',
            'smtp_port' => 465,
            'smtp_encryption' => 'ssl',
            'smtp_username' => 'imap-user',
            'smtp_password' => 'senha-antiga',
            'smtp_ehlo_domain' => 'example.com',
            'inbox_folder_name' => 'INBOX',
            'sent_folder_name' => 'Sent',
            'is_active' => true,
            'sync_interval_minutes' => 5,
        ]);

        Livewire::test(EditMailAccount::class, ['record' => $account->getRouteKey()])
            ->fillForm([
                'name' => 'Suporte',
                'email_address' => 'suporte@example.com',
                'from_name' => 'Suporte',
                'imap_host' => 'imap.example.com',
                'imap_port' => 993,
                'imap_encryption' => 'ssl',
                'imap_username' => 'imap-user',
                'imap_password' => 'senha-nova',
                'imap_validate_cert' => true,
                'use_imap_credentials_for_smtp' => true,
                'smtp_port' => 465,
                'smtp_ehlo_domain' => 'example.org',
                'inbox_folder_name' => 'INBOX',
                'sent_folder_name' => 'Sent',
                'is_active' => true,
                'sync_interval_minutes' => 5,
            ])
            ->call('save')
            ->assertNotified();

        $account->refresh();

        $this->assertSame('senha-nova', $account->imap_password);
        $this->assertSame('senha-nova', $account->smtp_password);
    $this->assertSame('example.org', $account->smtp_ehlo_domain);
    }

    private function authenticateAdmin(): User
    {
        $this->writeSettingsFile([
            'filament' => [
                'allowed_users' => [['email' => 'admin@teste.com']],
            ],
        ]);

        return User::factory()->create([
            'email' => 'admin@teste.com',
            'is_ativo' => true,
        ]);
    }
}
