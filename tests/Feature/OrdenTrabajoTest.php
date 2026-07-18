<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\EstadoOrden;
use App\Enums\TipoEquipo;
use App\Enums\TipoItem;
use App\Models\CatalogoItem;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\OrdenItem;
use App\Models\OrdenTrabajo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class OrdenTrabajoTest extends TestCase
{
    use RefreshDatabase;

    public function test_creacion_de_orden_registra_cliente_equipo_y_ot(): void
    {
        $tecnico = User::factory()->tecnico()->create();

        $response = $this->actingAs($tecnico)->post('/ordenes', [
            'cliente_dni' => '77778888',
            'cliente_nombre' => 'Nuevo Cliente',
            'cliente_telefono' => '999111222',
            'cliente_direccion' => 'Av. Test',
            'tipo' => TipoEquipo::Laptop->value,
            'marca' => 'Dell',
            'modelo' => 'Inspiron',
            'serie_imei' => 'SN-DELL-01',
            'estado_cosmetico' => 'Buen estado',
            'falla_reportada' => 'No prende',
            'password_desbloqueo' => 'abc123',
            'tecnico_id' => $tecnico->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('clientes', ['dni' => '77778888']);
        $this->assertDatabaseHas('equipos', ['marca' => 'Dell']);
        $this->assertDatabaseCount('ordenes_trabajo', 1);

        $equipo = Equipo::query()->first();
        $this->assertNotSame('abc123', $equipo->getRawOriginal('password_desbloqueo'));
        $this->assertSame('abc123', $equipo->password_desbloqueo);
    }

    public function test_agregar_item_bien_descuenta_stock_y_actualiza_total(): void
    {
        $tecnico = User::factory()->tecnico()->create();
        $equipo = Equipo::factory()->create();
        $orden = OrdenTrabajo::factory()->create(['equipo_id' => $equipo->id]);

        $bien = CatalogoItem::factory()->bien()->create([
            'nombre' => 'Pantalla test',
            'precio' => 150,
            'stock' => 5,
        ]);

        $response = $this->actingAs($tecnico)->post(
            "/ordenes/{$orden->id}/items",
            ['catalogo_item_id' => $bien->id, 'cantidad' => 2],
        );

        $response->assertRedirect();
        $bien->refresh();
        $orden->refresh();

        $this->assertSame(3, $bien->stock);
        $this->assertSame('300.00', $orden->total);
        $this->assertDatabaseHas('movimientos_inventario', [
            'catalogo_item_id' => $bien->id,
            'tipo' => 'salida',
            'cantidad' => 2,
        ]);
    }

    public function test_cambio_a_listo_para_entrega_recalcula_total(): void
    {
        $admin = User::factory()->admin()->create();
        $equipo = Equipo::factory()->create();
        $orden = OrdenTrabajo::factory()->create([
            'equipo_id' => $equipo->id,
            'estado' => EstadoOrden::EnReparacion,
            'total' => 0,
        ]);

        $servicio = CatalogoItem::factory()->servicio()->create(['precio' => 90]);
        OrdenItem::factory()->create([
            'orden_trabajo_id' => $orden->id,
            'catalogo_item_id' => $servicio->id,
            'tipo_snapshot' => TipoItem::Servicio,
            'nombre_snapshot' => $servicio->nombre,
            'cantidad' => 1,
            'precio_unitario' => 90,
            'subtotal' => 90,
        ]);

        $this->actingAs($admin)->put("/ordenes/{$orden->id}", [
            'estado' => EstadoOrden::ListoParaEntrega->value,
            'diagnostico' => 'Listo',
        ])->assertRedirect();

        $orden->refresh();
        $this->assertSame(EstadoOrden::ListoParaEntrega, $orden->estado);
        $this->assertSame('90.00', $orden->total);
    }

    public function test_registro_de_pago_actualiza_total_pagado(): void
    {
        $tecnico = User::factory()->tecnico()->create();
        $equipo = Equipo::factory()->create();
        $orden = OrdenTrabajo::factory()->create([
            'equipo_id' => $equipo->id,
            'total' => 200,
            'total_pagado' => 0,
        ]);

        $this->actingAs($tecnico)->post("/ordenes/{$orden->id}/pagos", [
            'metodo' => 'yape',
            'monto' => 200,
            'referencia' => 'YP-1',
        ])->assertRedirect();

        $orden->refresh();
        $this->assertSame('200.00', $orden->total_pagado);
        $this->assertDatabaseHas('pagos', ['orden_trabajo_id' => $orden->id, 'metodo' => 'yape']);
    }
}
