<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\EstadoOrden;
use App\Models\Cliente;
use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ConsultaPublicaController extends Controller
{
    public function formulario(): View
    {
        return view('consulta.formulario');
    }

    // RF-04: consulta publica por DNI, sin login.
    // RNF-04: solo se exponen nombre, modelo, estado y monto pendiente.
    public function buscar(Request $request): View
    {
        $data = $request->validate([
            'dni' => ['required', 'string', 'max:15'],
        ]);

        $cliente = Cliente::query()->porDni($data['dni'])->first();

        $ordenes = collect();

        if ($cliente !== null) {
            $ordenes = OrdenTrabajo::query()
                ->select([
                    'ordenes_trabajo.id',
                    'ordenes_trabajo.numero_ot',
                    'ordenes_trabajo.estado',
                    'ordenes_trabajo.total',
                    'ordenes_trabajo.total_pagado',
                    'ordenes_trabajo.recibido_at',
                    'ordenes_trabajo.entregado_at',
                    'ordenes_trabajo.equipo_id',
                ])
                ->with([
                    'equipo:id,cliente_id,tipo,marca,modelo',
                    'equipo.cliente:id,nombre',
                ])
                ->whereHas('equipo', static fn ($q) => $q->where('cliente_id', $cliente->id))
                ->orderByDesc('recibido_at')
                ->get();
        }

        return view('consulta.resultado', [
            'dni' => $data['dni'],
            'clienteEncontrado' => $cliente !== null,
            'ordenes' => $ordenes,
            'lineaTiempo' => EstadoOrden::lineaTiempo(),
        ]);
    }
}
