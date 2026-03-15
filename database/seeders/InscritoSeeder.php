<?php

namespace Database\Seeders;

use App\Models\Inscrito;
use App\Models\Loja;
use Illuminate\Database\Seeder;

class InscritoSeeder extends Seeder
{
    /**
     * Seed the application's database with fake inscritos.
     */
    public function run(): void
    {
        if (! Loja::query()->exists()) {
            $this->command?->warn('Nenhuma loja cadastrada. Cadastre lojas antes de executar o InscritoSeeder.');

            return;
        }

        Inscrito::factory()->count(100)->create();

        $this->command?->info('100 inscritos ficticios foram gerados com sucesso.');
    }
}
