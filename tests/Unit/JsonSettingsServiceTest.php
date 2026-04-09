<?php

namespace Tests\Unit;

use App\Support\JsonSettingsService;
use Tests\Concerns\InteractsWithSettingsFile;
use Tests\TestCase;

class JsonSettingsServiceTest extends TestCase
{
    use InteractsWithSettingsFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useTemporarySettingsFile('settings-service-test.json');
    }

    protected function tearDown(): void
    {
        $this->tearDownSettingsFile();

        parent::tearDown();
    }

    public function test_retorna_estrutura_padrao_quando_arquivo_esta_vazio(): void
    {
        file_put_contents($this->settingsTestPath, '');

        $settings = app(JsonSettingsService::class)->all();

        $this->assertSame([
            'filament' => [
                'allowed_users' => [],
            ],
        ], $settings);
    }

    public function test_retorna_estrutura_padrao_quando_json_e_invalido(): void
    {
        file_put_contents($this->settingsTestPath, '{invalid-json');

        $settings = app(JsonSettingsService::class)->all();

        $this->assertSame([], $settings['filament']['allowed_users']);
    }

    public function test_normaliza_duplicados_e_variacoes_de_caixa_ao_salvar(): void
    {
        $allowedUsers = app(JsonSettingsService::class)->saveAllowedUsers([
            ['name' => ' Admin ERAC ', 'email' => 'ADMIN@ERAC.TEST '],
            ['name' => 'Duplicado', 'email' => 'admin@erac.test'],
            ['name' => '', 'email' => 'segundo@erac.test'],
            ['name' => 'Invalido', 'email' => 'nao-e-email'],
        ]);

        $this->assertSame([
            [
                'name' => 'Admin ERAC',
                'email' => 'admin@erac.test',
            ],
            [
                'email' => 'segundo@erac.test',
            ],
        ], $allowedUsers);
    }
}
