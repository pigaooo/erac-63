<?php

namespace Database\Seeders;

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
        User::create([
            'name' => 'Admin ERAC',
            'email' => 'admi@erac.local',
            'password' => Hash::make('Erac61!'),
            'is_ativo' => true,
        ]);

        User::create([
            'name' => 'Thiago Sarkis',
            'email' => 'pigaooo@gmail.com',
            'password' => Hash::make('TSDFox0206!'),
            'is_ativo' => true,
        ]);

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

        // Patrocinadores removidos — manter apenas as lojas conforme solicitado.
    }
}
