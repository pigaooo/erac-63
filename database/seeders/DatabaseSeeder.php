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

        // Adiciona 10 patrocinadores gerados pela factory
        Patrocinador::factory()->count(10)->create();

        // Insere patrocinadores extras na categoria Bronze
        Patrocinador::insert([
            [
                'id' => Str::ulid(),
                'name' => 'Ateliê Bronze 1',
                'email' => 'contato@ateliebronze1.test',
                'telefone' => '(11) 99999-2006',
                'endereco' => 'https://ateliebronze1.test',
                'tipo_patrocinio' => 'Bronze',
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Loja Bronze 2',
                'email' => 'contato@lojabronze2.test',
                'telefone' => '(11) 99999-2007',
                'endereco' => 'https://lojabronze2.test',
                'tipo_patrocinio' => 'Bronze',
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Serviços Bronze 3',
                'email' => 'contato@servicosbronze3.test',
                'telefone' => '(11) 99999-2008',
                'endereco' => 'https://servicosbronze3.test',
                'tipo_patrocinio' => 'Bronze',
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Grupo Bronze 4',
                'email' => 'contato@grupobronze4.test',
                'telefone' => '(11) 99999-2009',
                'endereco' => 'https://grupobronze4.test',
                'tipo_patrocinio' => 'Bronze',
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Parceiro Bronze 5',
                'email' => 'contato@parceirobronze5.test',
                'telefone' => '(11) 99999-2010',
                'endereco' => 'https://parceirobronze5.test',
                'tipo_patrocinio' => 'Bronze',
            ],
        ]);
    }
}
