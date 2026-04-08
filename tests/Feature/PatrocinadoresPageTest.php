<?php

namespace Tests\Feature;

use App\Models\Patrocinador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PatrocinadoresPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagina_de_patrocinadores_retorna_ok_e_renderiza_titulo(): void
    {
        $response = $this->get(route('patrocinadores'));

        $response->assertOk();
        $response->assertSee('Marcas e apoiadores que fortalecem o ERAC 61');
    }

    public function test_pagina_exibe_patrocinadores_agrupados_na_ordem_esperada(): void
    {
        $diamante = $this->createPatrocinador('Casa Diamante', 'Diamante');
        $ouro = $this->createPatrocinador('Casa Ouro', 'Ouro');
        $prata = $this->createPatrocinador('Casa Prata', 'Prata');
        $bronze = $this->createPatrocinador('Casa Bronze', 'Bronze');
        $apoio = $this->createPatrocinador('Casa Apoio', 'Apoio');

        $response = $this->get(route('patrocinadores'));

        $response->assertOk();
        $response->assertSeeInOrder([
            'Patrocinadores diamante',
            $diamante->name,
            'Patrocinadores ouro',
            $ouro->name,
            'Patrocinadores prata',
            $prata->name,
            'Patrocinadores bronze',
            $bronze->name,
            'Apoiadores',
            $apoio->name,
        ], false);
    }

    public function test_apoio_aparece_sem_link_clicavel(): void
    {
        $apoiador = $this->createPatrocinador('Apoio Sem Link', 'Apoio', 'https://nao-deve-aparecer.test');

        $response = $this->get(route('patrocinadores'));

        $response->assertOk();
        $response->assertSee($apoiador->name);
        $response->assertDontSee('href="https://nao-deve-aparecer.test"', false);
    }

    private function createPatrocinador(string $name, string $tipo, ?string $endereco = 'https://empresa.test'): Patrocinador
    {
        return Patrocinador::query()->create([
            'id' => Str::ulid()->toBase32(),
            'name' => $name,
            'email' => Str::slug($name) . '@example.com',
            'telefone' => '(11) 99999-9999',
            'endereco' => $endereco,
            'tipo_patrocinio' => $tipo,
        ]);
    }
}
