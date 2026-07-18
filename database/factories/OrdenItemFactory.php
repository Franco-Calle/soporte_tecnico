<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CatalogoItem;
use App\Models\OrdenItem;
use App\Models\OrdenTrabajo;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<OrdenItem> */
final class OrdenItemFactory extends Factory
{
    protected $model = OrdenItem::class;

    public function definition(): array
    {
        /** @var CatalogoItem $item */
        $item = CatalogoItem::factory()->create();
        $cantidad = fake()->numberBetween(1, 3);

        return [
            'orden_trabajo_id' => OrdenTrabajo::factory(),
            'catalogo_item_id' => $item->id,
            'tipo_snapshot' => $item->tipo,
            'nombre_snapshot' => $item->nombre,
            'cantidad' => $cantidad,
            'precio_unitario' => $item->precio,
            'subtotal' => round((float) $item->precio * $cantidad, 2),
        ];
    }
}
