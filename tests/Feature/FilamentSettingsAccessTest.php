<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\Concerns\InteractsWithSettingsFile;
use Tests\TestCase;

class FilamentSettingsAccessTest extends TestCase
{
    use InteractsWithSettingsFile;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useTemporarySettingsFile('settings-access-test.json');
    }

    protected function tearDown(): void
    {
        $this->tearDownSettingsFile();

        Mockery::close();

        parent::tearDown();
    }

    public function test_usuario_ativo_e_listado_pode_acessar_o_filament(): void
    {
        $this->writeSettingsFile([
            'filament' => [
                'allowed_users' => [
                    ['email' => 'ativo@erac.test'],
                ],
            ],
        ]);

        $user = User::factory()->create([
            'email' => 'ativo@erac.test',
            'is_ativo' => true,
        ]);

        $this->assertTrue($user->canAccessPanel($this->mockPanel()));
    }

    public function test_usuario_ativo_nao_listado_nao_pode_acessar_o_filament(): void
    {
        $this->writeSettingsFile([
            'filament' => [
                'allowed_users' => [
                    ['email' => 'outro@erac.test'],
                ],
            ],
        ]);

        $user = User::factory()->create([
            'email' => 'ativo@erac.test',
            'is_ativo' => true,
        ]);

        $this->assertFalse($user->canAccessPanel($this->mockPanel()));
    }

    public function test_usuario_inativo_listado_nao_pode_acessar_o_filament(): void
    {
        $this->writeSettingsFile([
            'filament' => [
                'allowed_users' => [
                    ['email' => 'inativo@erac.test'],
                ],
            ],
        ]);

        $user = User::factory()->create([
            'email' => 'inativo@erac.test',
            'is_ativo' => false,
        ]);

        $this->assertFalse($user->canAccessPanel($this->mockPanel()));
    }

    private function mockPanel(): Panel
    {
        /** @var Panel $panel */
        $panel = Mockery::mock(Panel::class);

        return $panel;
    }
}
