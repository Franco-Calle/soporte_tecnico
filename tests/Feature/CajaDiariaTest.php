<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\MetodoPago;
use App\Models\Equipo;
use App\Models\OrdenTrabajo;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class CajaDiariaTest extends TestCase
{
    use RefreshDatabase;

    public function test_cierre_diario_suma_por_metodo_y_total(): void
    {
        $tecnico = User::factory()->tecnico()->create();
        $equipo = Equipo::factory()->create();
        $orden = OrdenTrabajo::factory()->create(['equipo_id' => $equipo->id]);

        $hoy = Carbon::today()->setHour(10);

        Pago::factory()->create(['orden_trabajo_id' => $orden->id, 'metodo' => MetodoPago::Efectivo, 'monto' => 100, 'cobrado_at' => $hoy]);
        Pago::factory()->create(['orden_trabajo_id' => $orden->id, 'metodo' => MetodoPago::Yape, 'monto' => 50, 'cobrado_at' => $hoy]);
        Pago::factory()->create(['orden_trabajo_id' => $orden->id, 'metodo' => MetodoPago::Plin, 'monto' => 30, 'cobrado_at' => $hoy]);
        // Pago de ayer (no debe contar).
        Pago::factory()->create(['orden_trabajo_id' => $orden->id, 'metodo' => MetodoPago::Transferencia, 'monto' => 500, 'cobrado_at' => $hoy->copy()->subDay()]);

        $response = $this->actingAs($tecnico)->get('/caja');

        $response->assertOk();
        $response->assertSee('S/. 100.00'); // efectivo
        $response->assertSee('S/. 50.00');  // yape
        $response->assertSee('S/. 180.00'); // total del dia
        $response->assertDontSee('S/. 500.00');
    }
}
