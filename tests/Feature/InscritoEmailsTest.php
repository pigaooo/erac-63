<?php

namespace Tests\Feature;

use App\Jobs\SendPaymentConfirmationEmail;
use App\Jobs\SendRegistrationConfirmationEmail;
use App\Livewire\InscricaoModal;
use App\Livewire\InscricaoMultiplos;
use App\Models\Inscrito;
use App\Models\Loja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class InscritoEmailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_envio_individual_coloca_email_de_confirmacao_na_fila(): void
    {
        Queue::fake();
        $loja = $this->createLoja();

        Livewire::test(InscricaoModal::class)
            ->set('nome', 'Irmao Individual')
            ->set('email', 'individual@example.com')
            ->set('telefone', '(11) 99999-9999')
            ->set('cpf', '111.111.111-11')
            ->set('cim', 'CIM-001')
            ->set('grau', 'AM')
            ->set('lojaId', $loja->id)
            ->call('submit');

        Queue::assertPushed(SendRegistrationConfirmationEmail::class, function (SendRegistrationConfirmationEmail $job) {
            return $job->registeredByStore === false;
        });
    }

    public function test_envio_em_lote_coloca_emails_de_confirmacao_na_fila(): void
    {
        Queue::fake();
        $loja = $this->createLoja();

        Livewire::test(InscricaoMultiplos::class)
            ->set('inscritos', [
                [
                    'name' => 'Irmao Um',
                    'email' => 'um@example.com',
                    'telefone' => '(11) 99999-9991',
                    'cpf' => '111.111.111-11',
                    'cim' => 'CIM-101',
                    'grau' => 'AM',
                    'loja_id' => $loja->id,
                ],
                [
                    'name' => 'Irmao Dois',
                    'email' => 'dois@example.com',
                    'telefone' => '(11) 99999-9992',
                    'cpf' => '222.222.222-22',
                    'cim' => 'CIM-102',
                    'grau' => 'MM',
                    'loja_id' => $loja->id,
                ],
            ])
            ->call('submit');

        Queue::assertPushed(SendRegistrationConfirmationEmail::class, 2);
        Queue::assertPushed(SendRegistrationConfirmationEmail::class, function (SendRegistrationConfirmationEmail $job) {
            return $job->registeredByStore === true;
        });
    }

    public function test_confirmar_pagamento_dispara_email_uma_vez(): void
    {
        Queue::fake();
        $loja = $this->createLoja();

        $inscrito = Inscrito::query()->create([
            'name' => 'Irmao Pago',
            'email' => 'pago@example.com',
            'telefone' => '(11) 99999-9999',
            'cpf' => '333.333.333-33',
            'cim' => 'CIM-201',
            'grau' => 'AM',
            'loja_id' => $loja->id,
            'is_paied' => false,
        ]);

        $inscrito->update([
            'is_paied' => true,
        ]);

        Queue::assertPushed(SendPaymentConfirmationEmail::class, 1);

        Queue::fake();

        $inscrito->refresh()->update([
            'name' => 'Irmao Pago Atualizado',
        ]);

        Queue::assertNothingPushed();
    }

    private function createLoja(): Loja
    {
        $user = User::factory()->create();

        return Loja::query()->create([
            'name' => 'Loja Teste',
            'numero_loja' => 1,
            'email' => 'loja@example.com',
            'is_ativo' => true,
            'user_id' => $user->id,
        ]);
    }
}
