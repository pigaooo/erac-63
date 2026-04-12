<?php

namespace Tests\Feature;

use App\Filament\Pages\Mailbox;
use App\Jobs\SendMailFromAccount;
use App\Models\Inscrito;
use App\Models\Loja;
use App\Models\MailAccount;
use App\Models\MailFolder;
use App\Models\MailMessage;
use App\Models\User;
use App\Support\Mail\Contracts\ImapClient;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithSettingsFile;
use Tests\Fakes\FakeImapClient;
use Tests\TestCase;

class MailMailboxPageTest extends TestCase
{
    use InteractsWithSettingsFile;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useTemporarySettingsFile('mailbox-page-test.json');
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    protected function tearDown(): void
    {
        $this->tearDownSettingsFile();
        Filament::setCurrentPanel(null);

        parent::tearDown();
    }

    public function test_abre_mensagem_e_hidrata_corpo_com_fake_imap(): void
    {
        $admin = $this->authenticateAdmin();
        $account = MailAccount::query()->create($this->accountData());
        $folder = MailFolder::query()->create([
            'mail_account_id' => $account->id,
            'remote_name' => 'INBOX',
            'display_name' => 'Entrada',
            'special_use' => 'inbox',
            'is_active' => true,
            'is_selectable' => true,
        ]);
        $message = MailMessage::query()->create([
            'mail_account_id' => $account->id,
            'mail_folder_id' => $folder->id,
            'uid' => 10,
            'subject' => 'Teste',
            'from_addresses' => [['address' => 'remetente@example.com', 'name' => null]],
            'to_addresses' => [['address' => 'destino@example.com', 'name' => null]],
            'direction' => 'inbound',
        ]);

        $fake = new FakeImapClient();
        $fake->messagePayloads['INBOX:10'] = [
            'text_body' => 'Conteudo sincronizado',
            'html_body' => null,
            'attachments' => [],
            'has_attachments' => false,
        ];
        $this->app->instance(ImapClient::class, $fake);

        $this->actingAs($admin);

        Livewire::test(Mailbox::class, ['account' => $account->id])
            ->set('selectedFolderId', $folder->id)
            ->call('openMessage', $message->id)
            ->assertSet('selectedMessageId', $message->id);

        $message->refresh();

        $this->assertSame('Conteudo sincronizado', $message->text_body);
        $this->assertTrue($message->is_seen);
    }

    public function test_envio_na_pagina_coloca_job_na_fila(): void
    {
        Queue::fake();

        $admin = $this->authenticateAdmin();
        $account = MailAccount::query()->create($this->accountData());
        MailFolder::query()->create([
            'mail_account_id' => $account->id,
            'remote_name' => 'INBOX',
            'display_name' => 'Entrada',
            'special_use' => 'inbox',
            'is_active' => true,
            'is_selectable' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(Mailbox::class, ['account' => $account->id])
            ->call('composeNew')
            ->set('composerData.to', 'destino@example.com')
            ->set('composerData.subject', 'Assunto teste')
            ->set('composerData.body', 'Corpo em markdown')
            ->call('sendComposer')
            ->assertSet('showComposer', false);

        Queue::assertPushed(SendMailFromAccount::class, function (SendMailFromAccount $job) use ($account) {
            return $job->mailAccountId === $account->id
                && $job->subject === 'Assunto teste';
        });
    }

    public function test_envio_na_pagina_aceita_multiplos_destinatarios_separados_por_virgula(): void
    {
        Queue::fake();

        $admin = $this->authenticateAdmin();
        $account = MailAccount::query()->create($this->accountData());
        MailFolder::query()->create([
            'mail_account_id' => $account->id,
            'remote_name' => 'INBOX',
            'display_name' => 'Entrada',
            'special_use' => 'inbox',
            'is_active' => true,
            'is_selectable' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(Mailbox::class, ['account' => $account->id])
            ->call('composeNew')
            ->set('composerRecipientInput', 'primeiro@example.com, segundo@example.com,')
            ->assertSet('composerRecipientInput', '')
            ->set('composerData.subject', 'Assunto varios')
            ->set('composerData.body', 'Corpo em markdown')
            ->call('sendComposer')
            ->assertSet('showComposer', false);

        Queue::assertPushed(SendMailFromAccount::class, 2);

        Queue::assertPushed(SendMailFromAccount::class, function (SendMailFromAccount $job) use ($account) {
            return $job->mailAccountId === $account->id
                && $job->subject === 'Assunto varios'
                && collect($job->to)->pluck('address')->all() === ['primeiro@example.com']
                && $job->bcc === [];
        });

        Queue::assertPushed(SendMailFromAccount::class, function (SendMailFromAccount $job) use ($account) {
            return $job->mailAccountId === $account->id
                && $job->subject === 'Assunto varios'
                && collect($job->to)->pluck('address')->all() === ['segundo@example.com']
                && $job->bcc === [];
        });
    }

    public function test_composer_sugere_inscritos_e_adiciona_destinatario_ao_selecionar(): void
    {
        $admin = $this->authenticateAdmin();
        $account = MailAccount::query()->create($this->accountData());
        MailFolder::query()->create([
            'mail_account_id' => $account->id,
            'remote_name' => 'INBOX',
            'display_name' => 'Entrada',
            'special_use' => 'inbox',
            'is_active' => true,
            'is_selectable' => true,
        ]);

        $loja = Loja::query()->create([
            'name' => 'Loja Central',
            'numero_loja' => 1,
            'email' => 'loja@example.com',
            'is_ativo' => true,
            'user_id' => $admin->id,
        ]);

        $inscrito = Inscrito::query()->create([
            'name' => 'Carlos Silva',
            'email' => 'carlos.silva@example.com',
            'telefone' => '(11) 99999-9999',
            'cpf' => '123.456.789-10',
            'cim' => '123456',
            'grau' => 'MM',
            'loja_id' => $loja->id,
            'is_paied' => false,
        ]);

        $this->actingAs($admin);

        Livewire::test(Mailbox::class, ['account' => $account->id])
            ->call('composeNew')
            ->set('composerRecipientInput', 'carlos')
            ->assertSee($inscrito->name)
            ->assertSee($inscrito->email)
            ->call('selectComposerSuggestion', $inscrito->email)
            ->assertSet('composerRecipientInput', '')
            ->assertSet('composerData.to', 'Carlos Silva <carlos.silva@example.com>');
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

    private function accountData(): array
    {
        return [
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
        ];
    }
}
