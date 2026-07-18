<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Cliente> */
final class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        return [
            'dni' => (string) fake()->unique()->numerify('########'),
            'nombre' => fake()->name(),
            'telefono' => fake()->numerify('9########'),
            'direccion' => fake()->streetAddress(),
        ];
    }
}
