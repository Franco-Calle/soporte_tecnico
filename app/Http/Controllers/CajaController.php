<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MetodoPago;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

final class CajaController extends Controller
{
    // RF-05: cierre de caja diario sumando efectivo, Yape, Plin y Transferencia.
    public function __invoke(Request $request): View
    {
        $fecha = $request->query('fecha')
            ? Carbon::parse($request->query('fecha'))
            : Carbon::today();

        $pagos = Pago::query()
            ->with(['ordenTrabajo:id,numero_ot', 'usuario:id,name'])
            ->delDia($fecha)
            ->orderBy('cobrado_at')
            ->get();

        $totalesPorMetodo = [];
        foreach (MetodoPago::cases() as $metodo) {
            $totalesPorMetodo[$metodo->value] = (float) $pagos
                ->where('metodo', $metodo)
                ->sum('monto');
        }

        return view('caja.index', [
            'fecha' => $fecha,
            'pagos' => $pagos,
            'totalesPorMetodo' => $totalesPorMetodo,
            'totalDia' => (float) $pagos->sum('monto'),
            'metodos' => MetodoPago::cases(),
        ]);
    }
}
