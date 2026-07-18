<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\EstadoOrden;
use App\Models\CatalogoItem;
use App\Models\OrdenTrabajo;
use App\Models\Pago;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $hoy = Carbon::today();

        $pendientes = OrdenTrabajo::query()
            ->with(['equipo.cliente', 'tecnico'])
            ->pendientes()
            ->latest('recibido_at')
            ->take(8)
            ->get();

        $conteosEstado = OrdenTrabajo::query()
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $stockBajo = CatalogoItem::query()->stockBajo()->get();

        $ingresosDia = Pago::query()->delDia($hoy)->sum('monto');

        return view('dashboard', [
            'pendientes' => $pendientes,
            'conteosEstado' => $conteosEstado,
            'stockBajo' => $stockBajo,
            'ingresosDia' => $ingresosDia,
            'estados' => EstadoOrden::cases(),
        ]);
    }
}
