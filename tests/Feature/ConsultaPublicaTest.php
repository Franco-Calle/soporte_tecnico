<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\EstadoOrden;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\OrdenTrabajo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ConsultaPublicaTest extends TestCase
{
    use RefreshDatabase;

    public function test_muestra_orden_al_ingresar_dni_valido(): void
    {
        $cliente = Cliente::factory()->create([
            'dni' => '44445555',
            'nombre' => 'Cliente Publico',
            'telefono' => '999888777',
            'direccion' => 'Calle Secreta 12',
        ]);
        $equipo = Equipo::factory()->create(['cliente_id' => $cliente->id]);
        $orden = OrdenTrabajo::factory()->create([
            'equipo_id' => $equipo->id,
            'numero_ot' => 'OT-000999',
            'estado' => EstadoOrden::EnReparacion,
            'total' => 200,
            'total_pagado' => 50,
        ]);

        $response = $this->post('/consulta', ['dni' => '44445555']);

        $response->assertOk();
        $response->assertSee('OT-000999');
        $response->assertSee('Cliente Publico');
        $response->assertSee($orden->estado->etiqueta());
    }

    // RNF-04: la consulta publica no debe exponer telefono, direccion ni password.
    public function test_no_expone_datos_sensibles_en_consulta_publica(): void
    {
        $cliente = Cliente::factory()->create([
            'dni' => '55556666',
            'telefono' => '911223344',
            'direccion' => 'Direccion Confidencial 123',
        ]);
        $equipo = Equipo::factory()->create([
            'cliente_id' => $cliente->id,
            'password_desbloqueo' => 'clave-super-secreta',
        ]);
        OrdenTrabajo::factory()->create(['equipo_id' => $equipo->id]);

        $response = $this->post('/consulta', ['dni' => '55556666']);

        $response->assertOk();
        $response->assertDontSee('911223344');
        $response->assertDontSee('Direccion Confidencial 123');
        $response->assertDontSee('clave-super-secreta');
    }

    public function test_mensaje_sin_resultados_para_dni_inexistente(): void
    {
        $response = $this->post('/consulta', ['dni' => '00000000']);
        $response->assertOk();
        $response->assertSee('Sin resultados');
    }
}
