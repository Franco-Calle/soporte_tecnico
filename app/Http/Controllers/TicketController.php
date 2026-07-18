<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

final class TicketController extends Controller
{
    public function ver(OrdenTrabajo $orden): View
    {
        $orden->load(['equipo.cliente', 'items', 'pagos', 'tecnico']);

        return view('tickets.comprobante', ['orden' => $orden]);
    }

    public function descargar(OrdenTrabajo $orden): Response
    {
        $orden->load(['equipo.cliente', 'items', 'pagos', 'tecnico']);

        $pdf = Pdf::loadView('tickets.comprobante', ['orden' => $orden])
            ->setPaper('a5', 'portrait');

        return $pdf->download("comprobante-{$orden->numero_ot}.pdf");
    }
}
