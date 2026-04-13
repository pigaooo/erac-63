<?php

namespace Tests\Feature;

use App\Filament\Resources\Settings\Pages\EditSettings;
use App\Filament\Resources\Settings\SettingsResource;
use App\Models\Settings;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithSettingsFile;
use Tests\TestCase;

class SettingsResourceTest extends TestCase
{
    use InteractsWithSettingsFile;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useTemporarySettingsFile('settings-resource-test.json');
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    protected function tearDown(): void
    {
        $this->tearDownSettingsFile();
        Filament::setCurrentPanel(null);

        parent::tearDown();
    }

    public function test_pagina_carrega_usuarios_atuais_do_json(): void
    {
        $this->writeSettingsFile([
            'filament' => [
                'allowed_users' => [
                    [
                        'name' => 'Admin ERAC',
                        'email' => 'admin@erac.test',
                    ],
                ],
            ],
        ]);

        $user = User::factory()->create([
            'email' => 'admin@erac.test',
            'is_ativo' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(EditSettings::class, ['record' => Settings::singletonKey()])
            ->assertFormSet([
                'allowed_users' => [
                    'admin@erac.test',
                ],
            ]);
    }

    public function test_pagina_adiciona_remove_e_salva_usuarios_no_json(): void
    {
        $this->writeSettingsFile([
            'filament' => [
                'allowed_users' => [
                    ['email' => 'admin@erac.test'],
                ],
            ],
        ]);

        $user = User::factory()->create([
            'email' => 'admin@erac.test',
            'is_ativo' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(EditSettings::class, ['record' => Settings::singletonKey()])
            ->fillForm([
                'allowed_users' => [
                    'NOVO@ERAC.TEST',
                ],
            ])
            ->call('save', false, true)
            ->assertNotified()
            ->assertHasNoFormErrors()
            ->assertFormSet([
                'allowed_users' => [
                    'novo@erac.test',
                ],
            ]);

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'filament' => [
                    'allowed_users' => [
                        [
                            'email' => 'novo@erac.test',
                        ],
                    ],
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            file_get_contents($this->settingsTestPath),
        );
    }

    public function test_seeder_sincroniza_usuarios_iniciais_no_settings_json(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertFileExists($this->settingsTestPath);
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'filament' => [
                    'allowed_users' => [
                        [
                            'name' => 'Admin ERAC',
                            'email' => 'admi@erac.local',
                        ],
                        [
                            'name' => 'Thiago Sarkis',
                            'email' => 'pigaooo@gmail.com',
                        ],
                    ],
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            file_get_contents($this->settingsTestPath),
        );
    }

    public function test_resource_gera_url_de_navegacao_para_o_singleton(): void
    {
        $this->assertStringContainsString(
            '/admin/settings/1/edit',
            SettingsResource::getNavigationUrl(),
        );
    }
}
