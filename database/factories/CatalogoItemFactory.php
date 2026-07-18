<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TipoEquipo;
use App\Enums\TipoItem;
use App\Models\CatalogoItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CatalogoItem> */
final class CatalogoItemFactory extends Factory
{
    protected $model = CatalogoItem::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->words(3, true),
            'descripcion' => fake()->sentence(),
            'tipo' => fake()->randomElement(TipoItem::cases()),
            'categoria_equipo' => fake()->randomElement(TipoEquipo::cases()),
            'precio' => fake()->randomFloat(2, 20, 500),
            'stock' => fake()->numberBetween(0, 20),
            'stock_minimo' => 1,
            'activo' => true,
        ];
    }

    public function servicio(): self
    {
        return $this->state(fn (): array => [
            'tipo' => TipoItem::Servicio,
            'stock' => 0,
            'stock_minimo' => 0,
        ]);
    }

    public function bien(): self
    {
        return $this->state(fn (): array => ['tipo' => TipoItem::Bien]);
    }
}
