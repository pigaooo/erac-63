<?php

namespace Database\Factories;

use App\Models\Patrocinador;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patrocinador>
 */
class PatrocinadorFactory extends Factory
{
    protected $model = Patrocinador::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'telefone' => fake()->numerify('(##) #####-####'),
            'endereco' => fake()->address(),
            'tipo_patrocinio' => fake()->randomElement(['Diamante', 'Ouro', 'Prata', 'Bronze', 'Apoio']),
            'user_id' => User::query()->inRandomOrder()->value('id'),
        ];
    }
}
