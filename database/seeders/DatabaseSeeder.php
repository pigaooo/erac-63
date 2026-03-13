<?php

namespace Database\Seeders;

use App\Models\Loja;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin ERAC',
            'email' => 'admin@erac.test',
            'password' => Hash::make('password'),
            'is_ativo' => true,
        ]);

        $lojas = [
            ['name' => 'Loja Acácia do Oriente', 'numero_loja' => 101, 'email' => 'contato@acaciaoriente.test'],
            ['name' => 'Loja Luz e Trabalho', 'numero_loja' => 102, 'email' => 'contato@luzetrabalho.test'],
            ['name' => 'Loja Fraternidade e Virtude', 'numero_loja' => 103, 'email' => 'contato@fraternidadevirtude.test'],
            ['name' => 'Loja Harmonia Universal', 'numero_loja' => 104, 'email' => 'contato@harmoniauniversal.test'],
            ['name' => 'Loja Colunas do Saber', 'numero_loja' => 105, 'email' => 'contato@colunasdosaber.test'],
            ['name' => 'Loja Templo da Verdade', 'numero_loja' => 106, 'email' => 'contato@templodaverdade.test'],
            ['name' => 'Loja Estrela do Sul', 'numero_loja' => 107, 'email' => 'contato@estreladosul.test'],
            ['name' => 'Loja Guardiões do Segredo', 'numero_loja' => 108, 'email' => 'contato@guardioesdosegredo.test'],
            ['name' => 'Loja Filhos da Luz', 'numero_loja' => 109, 'email' => 'contato@filhosdaluz.test'],
        ];

        foreach ($lojas as $loja) {
            Loja::create([
                ...$loja,
                'user_id' => $user->id,
                'is_ativo' => true,
            ]);
        }
    }
}
