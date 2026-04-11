<?php

namespace Tests\Unit;

use App\Models\MailAccount;
use App\Support\Mail\MailAccountManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailAccountManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_build_transport_uses_account_ehlo_domain(): void
    {
        $manager = new MailAccountManager();
        $transport = $manager->buildTransport($this->makeAccount());

        $this->assertSame('erac61.com.br', $transport->getLocalDomain());
    }

    public function test_build_transport_throws_when_account_ehlo_domain_is_missing(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('A conta de email precisa de um dominio EHLO/HELO configurado para enviar mensagens.');

        $manager = new MailAccountManager();
        $manager->buildTransport($this->makeAccount(['smtp_ehlo_domain' => null]));
    }

    private function makeAccount(array $overrides = []): MailAccount
    {
        return new MailAccount(array_merge([
            'name' => 'Inscricao',
            'email_address' => 'inscricao@erac61.com.br',
            'from_name' => 'ERAC 61',
            'smtp_host' => 'sh00220.hostgator.com.br',
            'smtp_port' => 465,
            'smtp_encryption' => 'ssl',
            'smtp_username' => 'inscricao@erac61.com.br',
            'smtp_password' => 'secret',
            'smtp_ehlo_domain' => 'erac61.com.br',
        ], $overrides));
    }
}