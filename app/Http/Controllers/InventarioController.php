<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\MovimientoInventarioRequest;
use App\Models\CatalogoItem;
use App\Models\MovimientoInventario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class InventarioController extends Controller
{
    // RF-03: panel simple para entradas/salidas de repuestos + alerta de stock minimo.
    public function index(): View
    {
        $items = CatalogoItem::query()->bienes()->orderBy('nombre')->get();
        $stockCritico = $items->filter(static fn (CatalogoItem $i): bool => $i->tieneStockCritico());
        $ultimosMovimientos = MovimientoInventario::query()
            ->with(['item', 'usuario', 'ordenTrabajo'])
            ->latest()
            ->take(20)
            ->get();

        return view('inventario.index', [
            'items' => $items,
            'stockCritico' => $stockCritico,
            'ultimosMovimientos' => $ultimosMovimientos,
        ]);
    }

    public function store(MovimientoInventarioRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request): void {
            /** @var CatalogoItem $item */
            $item = CatalogoItem::query()->findOrFail($data['catalogo_item_id']);
            $cantidad = (int) $data['cantidad'];

            $delta = match ($data['tipo']) {
                'entrada' => $cantidad,
                'salida' => -$cantidad,
                'ajuste' => $cantidad - (int) $item->stock,
                default => 0,
            };

            if ($data['tipo'] === 'salida' && $item->stock < $cantidad) {
                abort(422, 'Stock insuficiente para realizar la salida.');
            }

            if ($data['tipo'] === 'ajuste') {
                $item->update(['stock' => $cantidad]);
            } else {
                $item->increment('stock', $delta);
            }

            MovimientoInventario::query()->create([
                'catalogo_item_id' => $item->id,
                'usuario_id' => $request->user()?->id,
                'tipo' => $data['tipo'],
                'cantidad' => $data['tipo'] === 'ajuste' ? abs($delta) : $cantidad,
                'motivo' => $data['motivo'] ?? null,
            ]);
        });

        return redirect()->route('inventario.index')->with('status', 'Movimiento registrado.');
    }
}
