<?php

namespace Tests\Feature;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithSettingsFile;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use InteractsWithSettingsFile;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useTemporarySettingsFile('user-resource-test.json');
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    protected function tearDown(): void
    {
        $this->tearDownSettingsFile();
        Filament::setCurrentPanel(null);

        parent::tearDown();
    }

    public function test_cria_usuario_com_nome_email_senha_e_status(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@teste.com',
            'is_ativo' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Novo Usuario',
                'email' => 'novo@teste.com',
                'password' => 'SenhaForte123!',
                'is_ativo' => true,
            ])
            ->call('create')
            ->assertNotified()
            ->assertRedirect(UserResource::getUrl('index'));

        $user = User::query()->where('email', 'novo@teste.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue((bool) $user->is_ativo);
        $this->assertTrue(Hash::check('SenhaForte123!', $user->password));
    }

    public function test_edita_usuario_sem_trocar_senha(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@teste.com',
            'is_ativo' => true,
        ]);

        $user = User::factory()->create([
            'email' => 'usuario@teste.com',
            'password' => bcrypt('SenhaOriginal123!'),
            'is_ativo' => true,
        ]);

        $oldHash = $user->password;

        $this->actingAs($admin);

        Livewire::test(EditUser::class, ['record' => $user->id])
            ->fillForm([
                'name' => 'Usuario Atualizado',
                'email' => 'usuario@teste.com',
                'password' => '',
                'is_ativo' => false,
            ])
            ->call('save')
            ->assertNotified();

        $user->refresh();

        $this->assertSame('Usuario Atualizado', $user->name);
        $this->assertFalse((bool) $user->is_ativo);
        $this->assertSame($oldHash, $user->password);
    }

    public function test_edita_usuario_trocando_senha(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@teste.com',
            'is_ativo' => true,
        ]);

        $user = User::factory()->create([
            'email' => 'usuario@teste.com',
            'password' => bcrypt('SenhaOriginal123!'),
            'is_ativo' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(EditUser::class, ['record' => $user->id])
            ->fillForm([
                'name' => $user->name,
                'email' => $user->email,
                'password' => 'NovaSenha123!',
                'is_ativo' => true,
            ])
            ->call('save')
            ->assertNotified();

        $user->refresh();

        $this->assertTrue(Hash::check('NovaSenha123!', $user->password));
    }

    public function test_valida_email_unico_na_criacao(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@teste.com',
            'is_ativo' => true,
        ]);

        $existingUser = User::factory()->create([
            'email' => 'existente@teste.com',
        ]);

        $this->actingAs($admin);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Duplicado',
                'email' => $existingUser->email,
                'password' => 'SenhaForte123!',
                'is_ativo' => true,
            ])
            ->call('create')
            ->assertHasFormErrors(['email' => 'unique']);
    }

    public function test_listagem_filtra_por_status_ativo(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@teste.com',
            'is_ativo' => true,
        ]);

        $activeUser = User::factory()->create([
            'name' => 'Usuario Ativo',
            'is_ativo' => true,
        ]);

        $inactiveUser = User::factory()->create([
            'name' => 'Usuario Inativo',
            'is_ativo' => false,
        ]);

        $this->actingAs($admin);

        Livewire::test(ListUsers::class)
            ->filterTable('is_ativo', true)
            ->assertCanSeeTableRecords([$admin, $activeUser])
            ->assertCanNotSeeTableRecords([$inactiveUser]);
    }

    public function test_criar_usuario_nao_adiciona_email_automaticamente_ao_settings(): void
    {
        $this->writeSettingsFile([
            'filament' => [
                'allowed_users' => [
                    ['email' => 'admin@teste.com'],
                ],
            ],
        ]);

        $admin = User::factory()->create([
            'email' => 'admin@teste.com',
            'is_ativo' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Sem Allowlist',
                'email' => 'novo@teste.com',
                'password' => 'SenhaForte123!',
                'is_ativo' => true,
            ])
            ->call('create')
            ->assertNotified();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'filament' => [
                    'allowed_users' => [
                        ['email' => 'admin@teste.com'],
                    ],
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            file_get_contents($this->settingsTestPath),
        );
    }
}
