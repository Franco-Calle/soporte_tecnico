<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\EstadoOrden;
use App\Enums\Rol;
use App\Enums\TipoEquipo;
use App\Enums\TipoItem;
use App\Http\Requests\OrdenItemRequest;
use App\Http\Requests\OrdenTrabajoStoreRequest;
use App\Http\Requests\OrdenTrabajoUpdateRequest;
use App\Http\Requests\PagoRequest;
use App\Models\CatalogoItem;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\MovimientoInventario;
use App\Models\OrdenItem;
use App\Models\OrdenTrabajo;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class OrdenTrabajoController extends Controller
{
    public function index(Request $request): View
    {
        $estadoFiltro = $request->query('estado');

        $ordenes = OrdenTrabajo::query()
            ->with(['equipo.cliente', 'tecnico'])
            ->when($estadoFiltro !== null && $estadoFiltro !== '', static function ($q) use ($estadoFiltro): void {
                $q->where('estado', $estadoFiltro);
            })
            ->when($request->query('q'), static function ($q, $termino): void {
                $q->where(static function ($sub) use ($termino): void {
                    $sub->porNumeroOt($termino)
                        ->orWhereHas('equipo.cliente', static fn ($c) => $c->where('dni', 'like', "%{$termino}%"));
                });
            })
            ->orderByDesc('recibido_at')
            ->paginate(15)
            ->withQueryString();

        return view('ordenes.index', [
            'ordenes' => $ordenes,
            'estados' => EstadoOrden::cases(),
            'estadoActual' => $estadoFiltro,
        ]);
    }

    public function create(): View
    {
        return view('ordenes.create', [
            'tecnicos' => User::query()->where('rol', Rol::Tecnico->value)->where('activo', true)->get(),
            'tipos' => TipoEquipo::cases(),
        ]);
    }

    public function store(OrdenTrabajoStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $orden = DB::transaction(function () use ($data): OrdenTrabajo {
            $cliente = Cliente::query()->firstOrCreate(
                ['dni' => $data['cliente_dni']],
                [
                    'nombre' => $data['cliente_nombre'],
                    'telefono' => $data['cliente_telefono'] ?? null,
                    'direccion' => $data['cliente_direccion'] ?? null,
                ],
            );

            $equipo = Equipo::query()->create([
                'cliente_id' => $cliente->id,
                'tipo' => $data['tipo'],
                'marca' => $data['marca'],
                'modelo' => $data['modelo'],
                'serie_imei' => $data['serie_imei'] ?? null,
                'estado_cosmetico' => $data['estado_cosmetico'],
                'falla_reportada' => $data['falla_reportada'],
                'password_desbloqueo' => $data['password_desbloqueo'] ?? null,
            ]);

            return OrdenTrabajo::query()->create([
                'numero_ot' => $this->generarNumeroOt(),
                'equipo_id' => $equipo->id,
                'tecnico_id' => $data['tecnico_id'] ?? null,
                'estado' => EstadoOrden::Recibido,
                'total' => 0,
                'total_pagado' => 0,
            ]);
        });

        return redirect()->route('ordenes.show', $orden)
            ->with('status', 'Orden de trabajo creada exitosamente.');
    }

    public function show(OrdenTrabajo $orden): View
    {
        $orden->load([
            'equipo.cliente',
            'tecnico',
            'items.catalogoItem',
            'pagos.usuario',
        ]);

        return view('ordenes.show', [
            'orden' => $orden,
            'items' => $orden->items,
            'catalogoItems' => CatalogoItem::query()->activos()->orderBy('nombre')->get(),
            'estados' => EstadoOrden::cases(),
            'lineaTiempo' => EstadoOrden::lineaTiempo(),
        ]);
    }

    public function edit(OrdenTrabajo $orden): View
    {
        return view('ordenes.edit', [
            'orden' => $orden,
            'tecnicos' => User::query()->where('rol', Rol::Tecnico->value)->where('activo', true)->get(),
            'estados' => EstadoOrden::cases(),
        ]);
    }

    public function update(OrdenTrabajoUpdateRequest $request, OrdenTrabajo $orden): RedirectResponse
    {
        $data = $request->validated();
        $nuevoEstado = EstadoOrden::from($data['estado']);

        DB::transaction(function () use ($orden, $data, $nuevoEstado): void {
            $orden->update([
                'estado' => $nuevoEstado,
                'tecnico_id' => $data['tecnico_id'] ?? $orden->tecnico_id,
                'diagnostico' => $data['diagnostico'] ?? $orden->diagnostico,
                'notas_internas' => $data['notas_internas'] ?? $orden->notas_internas,
                'entregado_at' => $nuevoEstado === EstadoOrden::Entregado ? now() : $orden->entregado_at,
            ]);

            // RF-05: liquidacion automatica al pasar a "Listo para Entrega".
            if ($nuevoEstado === EstadoOrden::ListoParaEntrega) {
                $orden->refresh()->load('items');
                $total = (float) $orden->items->sum('subtotal');
                $orden->update(['total' => $total]);
            }
        });

        return redirect()->route('ordenes.show', $orden)
            ->with('status', 'Orden actualizada.');
    }

    // RF-02: agregar servicio o bien a la ficha (cruce ventas + servicios en una sola OT).
    public function agregarItem(OrdenItemRequest $request, OrdenTrabajo $orden): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($orden, $data, $request): void {
            /** @var CatalogoItem $item */
            $item = CatalogoItem::query()->findOrFail($data['catalogo_item_id']);
            $cantidad = (int) $data['cantidad'];

            if ($item->tipo === TipoItem::Bien && $item->stock < $cantidad) {
                abort(422, "Stock insuficiente para {$item->nombre}.");
            }

            OrdenItem::query()->create([
                'orden_trabajo_id' => $orden->id,
                'catalogo_item_id' => $item->id,
                'tipo_snapshot' => $item->tipo,
                'nombre_snapshot' => $item->nombre,
                'cantidad' => $cantidad,
                'precio_unitario' => $item->precio,
                'subtotal' => round((float) $item->precio * $cantidad, 2),
            ]);

            // Salida de stock automatica para bienes.
            if ($item->tipo === TipoItem::Bien) {
                $item->decrement('stock', $cantidad);

                MovimientoInventario::query()->create([
                    'catalogo_item_id' => $item->id,
                    'orden_trabajo_id' => $orden->id,
                    'usuario_id' => $request->user()?->id,
                    'tipo' => 'salida',
                    'cantidad' => $cantidad,
                    'motivo' => "Consumo en {$orden->numero_ot}",
                ]);
            }

            $orden->refresh()->load('items');
            $orden->update(['total' => (float) $orden->items->sum('subtotal')]);
        });

        return redirect()->route('ordenes.show', $orden)
            ->with('status', 'Item agregado a la orden.');
    }

    public function quitarItem(OrdenTrabajo $orden, OrdenItem $item): RedirectResponse
    {
        abort_unless($item->orden_trabajo_id === $orden->id, 404);

        DB::transaction(function () use ($orden, $item): void {
            // Devolver stock si era un bien.
            if ($item->tipo_snapshot === TipoItem::Bien) {
                $catalogoItem = $item->catalogoItem;
                if ($catalogoItem !== null) {
                    $catalogoItem->increment('stock', (int) $item->cantidad);

                    MovimientoInventario::query()->create([
                        'catalogo_item_id' => $catalogoItem->id,
                        'orden_trabajo_id' => $orden->id,
                        'usuario_id' => auth()->id(),
                        'tipo' => 'entrada',
                        'cantidad' => (int) $item->cantidad,
                        'motivo' => "Devolucion de {$orden->numero_ot}",
                    ]);
                }
            }

            $item->delete();

            $orden->refresh()->load('items');
            $orden->update(['total' => (float) $orden->items->sum('subtotal')]);
        });

        return redirect()->route('ordenes.show', $orden)
            ->with('status', 'Item removido.');
    }

    public function registrarPago(PagoRequest $request, OrdenTrabajo $orden): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($orden, $data, $request): void {
            Pago::query()->create([
                'orden_trabajo_id' => $orden->id,
                'registrado_por' => $request->user()?->id,
                'metodo' => $data['metodo'],
                'monto' => $data['monto'],
                'referencia' => $data['referencia'] ?? null,
                'cobrado_at' => now(),
            ]);

            $orden->refresh()->load('pagos');
            $orden->update(['total_pagado' => (float) $orden->pagos->sum('monto')]);
        });

        return redirect()->route('ordenes.show', $orden)
            ->with('status', 'Pago registrado.');
    }

    private function generarNumeroOt(): string
    {
        $ultima = OrdenTrabajo::query()->orderByDesc('id')->value('numero_ot');
        $nuevo = 1;
        if ($ultima !== null && preg_match('/(\d+)$/', (string) $ultima, $m) === 1) {
            $nuevo = (int) $m[1] + 1;
        }

        return 'OT-'.str_pad((string) $nuevo, 6, '0', STR_PAD_LEFT);
    }
}
