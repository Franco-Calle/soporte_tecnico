<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EstadoOrden;
use App\Models\Equipo;
use App\Models\OrdenTrabajo;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<OrdenTrabajo> */
final class OrdenTrabajoFactory extends Factory
{
    protected $model = OrdenTrabajo::class;

    public function definition(): array
    {
        return [
            'numero_ot' => 'OT-'.strtoupper(fake()->unique()->bothify('######')),
            'equipo_id' => Equipo::factory(),
            'tecnico_id' => null,
            'estado' => EstadoOrden::Recibido,
            'diagnostico' => null,
            'notas_internas' => null,
            'total' => 0,
            'total_pagado' => 0,
            'recibido_at' => now(),
            'entregado_at' => null,
        ];
    }

    public function enEstado(EstadoOrden $estado): self
    {
        return $this->state(fn (): array => ['estado' => $estado]);
    }
}
