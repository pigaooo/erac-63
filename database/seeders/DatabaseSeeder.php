<?php

namespace Database\Seeders;

use App\Models\Loja;
use App\Models\Patrocinador;
use App\Models\User;
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
        User::factory()->create([
            'name' => 'Admin ERAC',
            'email' => 'admin@erac.test',
            'password' => Hash::make('password'),
            'is_ativo' => true,
        ]);

        $userIds = User::query()->pluck('id');

        Loja::insert(collect([
            ['id' => Str::ulid(), 'name' => 'Loja Acacia do Oriente', 'numero_loja' => 101, 'email' => 'contato@acaciaoriente.test'],
            ['id' => Str::ulid(), 'name' => 'Loja Luz e Trabalho', 'numero_loja' => 102, 'email' => 'contato@luzetrabalho.test'],
            ['id' => Str::ulid(), 'name' => 'Loja Fraternidade e Virtude', 'numero_loja' => 103, 'email' => 'contato@fraternidadevirtude.test'],
            ['id' => Str::ulid(), 'name' => 'Loja Harmonia Universal', 'numero_loja' => 104, 'email' => 'contato@harmoniauniversal.test'],
            ['id' => Str::ulid(), 'name' => 'Loja Colunas do Saber', 'numero_loja' => 105, 'email' => 'contato@colunasdosaber.test'],
            ['id' => Str::ulid(), 'name' => 'Loja Templo da Verdade', 'numero_loja' => 106, 'email' => 'contato@templodaverdade.test'],
            ['id' => Str::ulid(), 'name' => 'Loja Estrela do Sul', 'numero_loja' => 107, 'email' => 'contato@estreladosul.test'],
            ['id' => Str::ulid(), 'name' => 'Loja Guardioes do Segredo', 'numero_loja' => 108, 'email' => 'contato@guardioesdosegredo.test'],
            ['id' => Str::ulid(), 'name' => 'Loja Filhos da Luz', 'numero_loja' => 109, 'email' => 'contato@filhosdaluz.test'],
        ])->map(function (array $loja) use ($userIds) {
            $loja['user_id'] = $userIds->random();

            return $loja;
        })->all());

        Patrocinador::insert([
            [
                'id' => Str::ulid(),
                'name' => 'Casa do Oriente',
                'email' => 'contato@casadooriente.test',
                'telefone' => '(11) 99999-1001',
                'endereco' => 'https://casadooriente.test',
                'tipo_patrocinio' => 'Diamante',
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Atelie Colunas',
                'email' => 'contato@ateliecolunas.test',
                'telefone' => '(11) 99999-1002',
                'endereco' => 'https://ateliecolunas.test',
                'tipo_patrocinio' => 'Ouro',
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Templo Shop',
                'email' => 'contato@temploshop.test',
                'telefone' => '(11) 99999-1003',
                'endereco' => 'https://temploshop.test',
                'tipo_patrocinio' => 'Prata',
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Luz Editorial',
                'email' => 'contato@luzeditorial.test',
                'telefone' => '(11) 99999-1004',
                'endereco' => 'https://luzeditorial.test',
                'tipo_patrocinio' => 'Bronze',
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Fraternidade Eventos',
                'email' => 'contato@fraternidadeeventos.test',
                'telefone' => '(11) 99999-1005',
                'endereco' => 'https://fraternidadeeventos.test',
                'tipo_patrocinio' => 'Apoio',
            ],
        ]);
    }
}
