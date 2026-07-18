<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CatalogoItem;
use App\Models\MovimientoInventario;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MovimientoInventario> */
final class MovimientoInventarioFactory extends Factory
{
    protected $model = MovimientoInventario::class;

    public function definition(): array
    {
        return [
            'catalogo_item_id' => CatalogoItem::factory(),
            'orden_trabajo_id' => null,
            'usuario_id' => null,
            'tipo' => 'entrada',
            'cantidad' => fake()->numberBetween(1, 10),
            'motivo' => 'Ingreso de stock',
        ];
    }
}
