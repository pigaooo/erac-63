<?php

namespace Database\Seeders;

use App\Models\Grau;
use App\Models\Loja;
use App\Models\User;
use App\Support\JsonSettingsService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure Admin ERAC exists and has the requested password
        User::updateOrCreate(
            ['email' => 'admin@erac.test'],
            [
                'name' => 'Admin ERAC',
                'password' => Hash::make('Erac61!'),
                'is_ativo' => true,
            ]
        );

        // Ensure Thiago user exists
        User::updateOrCreate(
            ['email' => 'pigaooo@gmail.com'],
            [
                'name' => 'Thiago Sarkis',
                'password' => Hash::make('TSDfox0206!'),
                'is_ativo' => true,
                'email_verified_at' => now(),
            ]
        );

        // Sync allowed Filament users from the database users
        app(JsonSettingsService::class)->syncAllowedUsers(
            User::query()->get(['name', 'email'])
        );

        $userIds = User::query()->pluck('id');

       

        $nomesLojas = [
            'Acacia de Ubatuba',
            'Amor a Ordem Respeitada',
            'Fraternidade Academica Sementes do Amanha',
            'Estrela Vega',
            'Vigilantes de Taubate',
            'Fraternidade Academica Irmao Jose Geraldo Trani Brandao',
            'Fraternidade Academica Luciano Alfredo Vianna do Rio',
            'Arquitetos da Harmonia',
            'Independencia e Lealdade',
            'Integridade e Justica',
            'Luz do Oriente',
            'Luz, Vida e Amor',
            'Solidariedade do Paraitinga',
            'Templarios da Paz',
            'Uniao das Americas',
            'Uniao, Forca e Vigor',
            'Universitaria Cavaleiros do Sol',
            'Visitante',
            'Fonte de Vida',
            'Vinte e Um de Abril',
            'Natureza e Fraternidade',
            'Fraternidade e Integridade Taubateana',
            'Colunas de Luz',
        ];

        Loja::insert(collect($nomesLojas)->values()->map(function (string $nome, int $index) use ($userIds) {
            $slug = Str::slug($nome, '');

            $loja = [
                'id' => Str::ulid(),
                'name' => $nome,
                'numero_loja' => 101 + $index,
                'email' => "contato@{$slug}.test",
            ];

            $loja['user_id'] = $userIds->random();

            return $loja;
        })->all());
        Grau::insert([
            [
                'id' => (string) Str::ulid(),
                'codigo' => 'AM',
                'nome' => 'A∴M∴',
                'ordem' => 10,
                'ativo' => true,
                'tipo_especial' => false,
                'disponivel_formulario_individual' => true,
                'disponivel_formulario_multiplos' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'codigo' => 'CM',
                'nome' => 'C∴M∴',
                'ordem' => 20,
                'ativo' => true,
                'tipo_especial' => false,
                'disponivel_formulario_individual' => true,
                'disponivel_formulario_multiplos' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'codigo' => 'MM',
                'nome' => 'M∴M∴',
                'ordem' => 30,
                'ativo' => true,
                'tipo_especial' => false,
                'disponivel_formulario_individual' => true,
                'disponivel_formulario_multiplos' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'codigo' => 'MI',
                'nome' => 'M∴I∴',
                'ordem' => 40,
                'ativo' => true,
                'tipo_especial' => false,
                'disponivel_formulario_individual' => true,
                'disponivel_formulario_multiplos' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'codigo' => 'OT',
                'nome' => 'Outros',
                'ordem' => 50,
                'ativo' => true,
                'tipo_especial' => true,
                'disponivel_formulario_individual' => true,
                'disponivel_formulario_multiplos' => true,
                'created_at' => now(),  
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'codigo' => 'VI',
                'nome' => 'Visitante',
                'ordem' => 60,
                'ativo' => true,
                'tipo_especial' => true,
                'disponivel_formulario_individual' => true,  
                'disponivel_formulario_multiplos' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'codigo' => 'CU',
                'nome' => 'Cunhada',
                'ordem' => 70,
                'ativo' => true,
                'tipo_especial' => true,
                'disponivel_formulario_individual' => true,
                'disponivel_formulario_multiplos' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'codigo' => 'SO',
                'nome' => 'Sobrinho',
                'ordem' => 80,
                'ativo' => true,
                'tipo_especial' => true,
                'disponivel_formulario_individual' => true,
                'disponivel_formulario_multiplos' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'codigo' => 'EX',
                'nome' => 'Expositor',
                'ordem' => 90,
                'ativo' => true,
                'tipo_especial' => true,
                'disponivel_formulario_individual' => true,
                'disponivel_formulario_multiplos' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::ulid(),
                'codigo' => 'PA',
                'nome' => 'Patrocinador',
                'ordem' => 100,
                'ativo' => true,
                'tipo_especial' => true,
                'disponivel_formulario_individual' => true,
                'disponivel_formulario_multiplos' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        // Patrocinadores removidos — manter apenas as lojas conforme solicitado.
    }
}
