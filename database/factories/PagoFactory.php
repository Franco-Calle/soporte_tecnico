<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MetodoPago;
use App\Models\OrdenTrabajo;
use App\Models\Pago;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Pago> */
final class PagoFactory extends Factory
{
    protected $model = Pago::class;

    public function definition(): array
    {
        return [
            'orden_trabajo_id' => OrdenTrabajo::factory(),
            'registrado_por' => null,
            'metodo' => fake()->randomElement(MetodoPago::cases()),
            'monto' => fake()->randomFloat(2, 20, 300),
            'referencia' => null,
            'cobrado_at' => now(),
        ];
    }
}
