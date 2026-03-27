<?php

namespace Tests\Feature;

use App\Livewire\InscricaoModal;
use App\Livewire\InscricaoMultiplos;
use App\Models\Loja;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InscricaoPageTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();

        parent::tearDown();
    }

    public function test_pagina_remove_blocos_apos_encerramento_online(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-06-21 10:00:00', 'America/Sao_Paulo'));

        $response = $this->get(route('inscricao'));

        $response->assertOk();
        $response->assertSee('somente no local do evento');
        $response->assertDontSee('id="inscricao-individual"', false);
        $response->assertDontSee('id="inscricao-multipla"', false);
    }

    public function test_livewire_bloqueia_envio_individual_fora_do_prazo(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-06-21 10:00:00', 'America/Sao_Paulo'));
        $loja = $this->createLoja();

        Livewire::test(InscricaoModal::class)
            ->set('nome', 'Teste Irmao')
            ->set('email', 'teste@example.com')
            ->set('telefone', '(11) 99999-9999')
            ->set('cpf', '000.000.000-00')
            ->set('grau', 'AM')
            ->set('lojaId', $loja->id)
            ->call('submit')
            ->assertHasErrors(['inscricoes']);

        $this->assertDatabaseCount('inscritos', 0);
    }

    public function test_livewire_bloqueia_envio_em_lote_fora_do_prazo(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-06-21 10:00:00', 'America/Sao_Paulo'));
        $loja = $this->createLoja();

        Livewire::test(InscricaoMultiplos::class)
            ->set('inscritos', [[
                'name' => 'Teste Irmao',
                'email' => 'teste-lote@example.com',
                'telefone' => '(11) 99999-9999',
                'cpf' => '000.000.000-00',
                'cim' => 'CIM-001',
                'grau' => 'AM',
                'loja_id' => $loja->id,
            ]])
            ->call('submit')
            ->assertHasErrors(['inscricoes']);

        $this->assertDatabaseCount('inscritos', 0);
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
