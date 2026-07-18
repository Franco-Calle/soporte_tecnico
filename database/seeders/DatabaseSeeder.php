<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\EstadoOrden;
use App\Enums\MetodoPago;
use App\Enums\Rol;
use App\Enums\TipoEquipo;
use App\Enums\TipoItem;
use App\Models\CatalogoItem;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\MovimientoInventario;
use App\Models\OrdenItem;
use App\Models\OrdenTrabajo;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Administrador',
            'email' => 'admin@taller.local',
            'password' => Hash::make('password'),
        ]);

        $tecnico = User::factory()->tecnico()->create([
            'name' => 'Tecnico Demo',
            'email' => 'tecnico@taller.local',
            'password' => Hash::make('password'),
        ]);

        // Catalogo: servicios por categoria de equipo.
        $servicios = [
            [TipoEquipo::Escritorio, 'Mantenimiento preventivo Escritorio', 60.00],
            [TipoEquipo::Escritorio, 'Instalacion de sistema operativo', 80.00],
            [TipoEquipo::Escritorio, 'Instalacion de antivirus', 40.00],
            [TipoEquipo::Laptop, 'Limpieza interna Laptop', 70.00],
            [TipoEquipo::Laptop, 'Cambio de pantalla Laptop', 120.00],
            [TipoEquipo::Laptop, 'Instalacion de sistema operativo', 80.00],
            [TipoEquipo::Celular, 'Cambio de pantalla tactil Celular', 90.00],
            [TipoEquipo::Celular, 'Formateo Celular', 45.00],
        ];

        foreach ($servicios as [$categoria, $nombre, $precio]) {
            CatalogoItem::factory()->servicio()->create([
                'nombre' => $nombre,
                'descripcion' => 'Servicio tecnico especializado',
                'categoria_equipo' => $categoria,
                'precio' => $precio,
                'stock' => 0,
                'stock_minimo' => 0,
            ]);
        }

        // Catalogo: bienes/repuestos con stock variado (uno con stock critico).
        $bienes = [
            [TipoEquipo::Escritorio, 'Memoria RAM DDR4 8GB', 130.00, 5, 2],
            [TipoEquipo::Escritorio, 'SSD 240GB SATA', 155.00, 3, 1],
            [TipoEquipo::Laptop, 'Cargador universal Laptop 65W', 90.00, 8, 2],
            [TipoEquipo::Laptop, 'Pantalla 14 pulgadas HD', 320.00, 1, 1], // stock critico (RF-03)
            [TipoEquipo::Celular, 'Pantalla tactil generica', 180.00, 4, 1],
            [TipoEquipo::Celular, 'Bateria Xiaomi Redmi', 85.00, 6, 2],
            [TipoEquipo::Escritorio, 'Licencia Antivirus 1 anio', 35.00, 0, 1], // stock 0 (alerta)
        ];

        foreach ($bienes as [$categoria, $nombre, $precio, $stock, $stockMin]) {
            CatalogoItem::factory()->bien()->create([
                'nombre' => $nombre,
                'descripcion' => 'Repuesto en stock',
                'categoria_equipo' => $categoria,
                'precio' => $precio,
                'stock' => $stock,
                'stock_minimo' => $stockMin,
            ]);

            MovimientoInventario::factory()->create([
                'catalogo_item_id' => CatalogoItem::query()->latest('id')->first()->id,
                'usuario_id' => $admin->id,
                'tipo' => 'entrada',
                'cantidad' => $stock,
                'motivo' => 'Stock inicial',
            ]);
        }

        // Clientes demo con DNI conocidos para probar la consulta publica.
        $clienteJuan = Cliente::factory()->create([
            'dni' => '12345678',
            'nombre' => 'Juan Perez',
            'telefono' => '987654321',
            'direccion' => 'Av. Principal 123, Lima',
        ]);
        $clienteMaria = Cliente::factory()->create([
            'dni' => '87654321',
            'nombre' => 'Maria Lopez',
            'telefono' => '912345678',
            'direccion' => 'Jr. Los Olivos 456, Lima',
        ]);
        $clienteCarlos = Cliente::factory()->create([
            'dni' => '11223344',
            'nombre' => 'Carlos Diaz',
            'telefono' => '955443322',
            'direccion' => 'Calle Union 789, Lima',
        ]);

        // OT #1: Juan - Laptop en reparacion con pago parcial.
        $equipoJuan = Equipo::factory()->create([
            'cliente_id' => $clienteJuan->id,
            'tipo' => TipoEquipo::Laptop,
            'marca' => 'HP',
            'modelo' => 'Pavilion 14',
            'serie_imei' => 'SN-HP-2201',
            'estado_cosmetico' => 'Rayones menores en la tapa',
            'falla_reportada' => 'Pantalla no enciende',
            'password_desbloqueo' => '1234',
        ]);

        $otJuan = OrdenTrabajo::factory()->create([
            'numero_ot' => 'OT-000001',
            'equipo_id' => $equipoJuan->id,
            'tecnico_id' => $tecnico->id,
            'estado' => EstadoOrden::EnReparacion,
            'diagnostico' => 'Requiere cambio de pantalla',
            'total' => 0,
            'total_pagado' => 0,
        ]);

        $pantallaLaptop = CatalogoItem::query()->where('nombre', 'Pantalla 14 pulgadas HD')->first();
        $servicioCambio = CatalogoItem::query()->where('nombre', 'Cambio de pantalla Laptop')->first();

        OrdenItem::factory()->create([
            'orden_trabajo_id' => $otJuan->id,
            'catalogo_item_id' => $pantallaLaptop->id,
            'tipo_snapshot' => TipoItem::Bien,
            'nombre_snapshot' => $pantallaLaptop->nombre,
            'cantidad' => 1,
            'precio_unitario' => $pantallaLaptop->precio,
            'subtotal' => $pantallaLaptop->precio,
        ]);
        OrdenItem::factory()->create([
            'orden_trabajo_id' => $otJuan->id,
            'catalogo_item_id' => $servicioCambio->id,
            'tipo_snapshot' => TipoItem::Servicio,
            'nombre_snapshot' => $servicioCambio->nombre,
            'cantidad' => 1,
            'precio_unitario' => $servicioCambio->precio,
            'subtotal' => $servicioCambio->precio,
        ]);

        $totalJuan = (float) $pantallaLaptop->precio + (float) $servicioCambio->precio;
        $otJuan->update(['total' => $totalJuan, 'total_pagado' => 100]);

        Pago::factory()->create([
            'orden_trabajo_id' => $otJuan->id,
            'registrado_por' => $tecnico->id,
            'metodo' => MetodoPago::Efectivo,
            'monto' => 100,
            'referencia' => 'Adelanto en efectivo',
        ]);

        // OT #2: Maria - Celular en diagnostico.
        $equipoMaria = Equipo::factory()->create([
            'cliente_id' => $clienteMaria->id,
            'tipo' => TipoEquipo::Celular,
            'marca' => 'Xiaomi',
            'modelo' => 'Redmi Note 12',
            'serie_imei' => '356938035643809',
            'estado_cosmetico' => 'Golpe leve en la esquina inferior',
            'falla_reportada' => 'No carga bateria',
            'password_desbloqueo' => '0000',
        ]);

        OrdenTrabajo::factory()->create([
            'numero_ot' => 'OT-000002',
            'equipo_id' => $equipoMaria->id,
            'tecnico_id' => $tecnico->id,
            'estado' => EstadoOrden::EnDiagnostico,
            'total' => 0,
            'total_pagado' => 0,
        ]);

        // OT #3: Carlos - Escritorio ya entregada (para probar cierre de caja).
        $equipoCarlos = Equipo::factory()->create([
            'cliente_id' => $clienteCarlos->id,
            'tipo' => TipoEquipo::Escritorio,
            'marca' => 'Lenovo',
            'modelo' => 'ThinkCentre M70',
            'serie_imei' => 'SN-LN-3391',
            'estado_cosmetico' => 'Sin ralladuras',
            'falla_reportada' => 'Muy lento',
            'password_desbloqueo' => null,
        ]);

        $otCarlos = OrdenTrabajo::factory()->create([
            'numero_ot' => 'OT-000003',
            'equipo_id' => $equipoCarlos->id,
            'tecnico_id' => $tecnico->id,
            'estado' => EstadoOrden::Entregado,
            'diagnostico' => 'Disco duro degradado',
            'total' => 235,
            'total_pagado' => 235,
            'entregado_at' => now(),
        ]);

        $ssd = CatalogoItem::query()->where('nombre', 'SSD 240GB SATA')->first();
        $servInstalar = CatalogoItem::query()->where('nombre', 'Instalacion de sistema operativo')
            ->where('categoria_equipo', TipoEquipo::Escritorio->value)->first();

        OrdenItem::factory()->create([
            'orden_trabajo_id' => $otCarlos->id,
            'catalogo_item_id' => $ssd->id,
            'tipo_snapshot' => TipoItem::Bien,
            'nombre_snapshot' => $ssd->nombre,
            'cantidad' => 1,
            'precio_unitario' => $ssd->precio,
            'subtotal' => $ssd->precio,
        ]);
        OrdenItem::factory()->create([
            'orden_trabajo_id' => $otCarlos->id,
            'catalogo_item_id' => $servInstalar->id,
            'tipo_snapshot' => TipoItem::Servicio,
            'nombre_snapshot' => $servInstalar->nombre,
            'cantidad' => 1,
            'precio_unitario' => $servInstalar->precio,
            'subtotal' => $servInstalar->precio,
        ]);

        Pago::factory()->create([
            'orden_trabajo_id' => $otCarlos->id,
            'registrado_por' => $tecnico->id,
            'metodo' => MetodoPago::Yape,
            'monto' => 235,
            'referencia' => 'Yape #9988',
        ]);
    }
}
