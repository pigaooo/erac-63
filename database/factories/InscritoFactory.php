<?php

namespace Database\Factories;

use App\Models\Inscrito;
use App\Models\Loja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inscrito>
 */
class InscritoFactory extends Factory
{
    protected $model = Inscrito::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telefone' => fake()->numerify('(##) #####-####'),
            'cpf' => fake()->unique()->numerify('###.###.###-##'),
            'cim' => fake()->unique()->numerify('######'),
            'grau' => fake()->randomElement(['AM', 'CM', 'MM', 'MI']),
            'loja_id' => Loja::query()->inRandomOrder()->value('id'),
            'is_paied' => fake()->boolean(35),
        ];
    }

    public function pago(): static
    {
        return $this->state(fn () => [
            'is_paied' => true,
        ]);
    }

    public function naoPago(): static
    {
        return $this->state(fn () => [
            'is_paied' => false,
        ]);
    }
}
