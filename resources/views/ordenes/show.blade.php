@extends('layouts.app')

@section('titulo', 'Orden ' . $orden->numero_ot)

@section('contenido')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <div>
            <p class="text-xs uppercase tracking-wider text-slate-500">Orden de trabajo</p>
            <h2 class="text-2xl font-semibold text-primario">{{ $orden->numero_ot }}</h2>
            <span class="badge {{ $orden->estado->colorBadge() }}">{{ $orden->estado->etiqueta() }}</span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('ordenes.edit', $orden) }}" class="btn-secondary">Actualizar estado</a>
            @if($orden->estaLiquidada())
                <a href="{{ route('tickets.ver', $orden) }}" class="btn-primary">Comprobante</a>
            @endif
        </div>
    </div>

    {{-- Linea de tiempo (RF-04) --}}
    <div class="card mb-6">
        <p class="text-xs uppercase tracking-wider text-slate-500 mb-3">Progreso</p>
        <ol class="grid grid-cols-2 md:grid-cols-6 gap-2">
            @foreach($lineaTiempo as $estadoLinea)
                @php
                    $completado = $estadoLinea->orden() <= $orden->estado->orden();
                    $actual = $estadoLinea === $orden->estado;
                @endphp
                <li class="rounded-md border p-2 text-xs text-center
                           {{ $actual ? 'border-secundario bg-fondo-suave font-semibold text-primario'
                                      : ($completado ? 'border-exito bg-exito/40 text-primario'
                                                     : 'border-slate-200 bg-white text-slate-400') }}">
                    {{ $estadoLinea->etiqueta() }}
                </li>
            @endforeach
        </ol>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="card">
            <h3 class="font-semibold text-primario mb-2">Cliente y equipo</h3>
            <dl class="text-sm space-y-1">
                <div class="flex justify-between"><dt class="text-slate-500">Cliente</dt><dd>{{ $orden->equipo->cliente->nombre }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">DNI</dt><dd>{{ $orden->equipo->cliente->dni }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Telefono</dt><dd>{{ $orden->equipo->cliente->telefono ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Equipo</dt><dd>{{ $orden->equipo->tipo->etiqueta() }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Marca / Modelo</dt><dd>{{ $orden->equipo->marca }} {{ $orden->equipo->modelo }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">{{ $orden->equipo->tipo->identificadorLabel() }}</dt><dd>{{ $orden->equipo->serie_imei ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Estado cosmetico</dt><dd class="text-right max-w-[60%]">{{ $orden->equipo->estado_cosmetico }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Falla reportada</dt><dd class="text-right max-w-[60%]">{{ $orden->equipo->falla_reportada }}</dd></div>
                @if($orden->equipo->password_desbloqueo)
                    <div class="flex justify-between border-t border-slate-100 pt-2 mt-2">
                        <dt class="text-slate-500">Password desbloqueo</dt>
                        <dd class="font-mono">{{ $orden->equipo->password_desbloqueo }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="card">
            <h3 class="font-semibold text-primario mb-2">Resumen economico</h3>
            <dl class="text-sm space-y-1">
                <div class="flex justify-between"><dt class="text-slate-500">Total</dt><dd class="font-semibold">S/. {{ number_format((float) $orden->total, 2) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Pagado</dt><dd>S/. {{ number_format((float) $orden->total_pagado, 2) }}</dd></div>
                <div class="flex justify-between text-primario font-semibold pt-2 border-t border-slate-100">
                    <dt>Pendiente</dt>
                    <dd>S/. {{ number_format($orden->saldoPendiente(), 2) }}</dd>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-100"><dt class="text-slate-500">Recibido</dt><dd>{{ $orden->recibido_at->format('d/m/Y H:i') }}</dd></div>
                @if($orden->entregado_at)
                    <div class="flex justify-between"><dt class="text-slate-500">Entregado</dt><dd>{{ $orden->entregado_at->format('d/m/Y H:i') }}</dd></div>
                @endif
                <div class="flex justify-between"><dt class="text-slate-500">Tecnico</dt><dd>{{ $orden->tecnico?->name ?? 'Sin asignar' }}</dd></div>
            </dl>
        </div>
    </div>

    {{-- Items (RF-02) --}}
    <div class="card mt-6">
        <h3 class="font-semibold text-primario mb-3">Servicios y repuestos</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-slate-500 border-b border-slate-200">
                    <tr><th class="py-2">Tipo</th><th class="py-2">Item</th><th class="py-2 text-right">Cant.</th><th class="py-2 text-right">P. Unit</th><th class="py-2 text-right">Subtotal</th><th></th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $it)
                        <tr>
                            <td class="py-2">
                                <span class="badge {{ $it->tipo_snapshot->value === 'servicio' ? 'badge-info' : 'bg-slate-100 text-secundario' }}">
                                    {{ $it->tipo_snapshot->etiqueta() }}
                                </span>
                            </td>
                            <td class="py-2">{{ $it->nombre_snapshot }}</td>
                            <td class="py-2 text-right">{{ $it->cantidad }}</td>
                            <td class="py-2 text-right">S/. {{ number_format((float) $it->precio_unitario, 2) }}</td>
                            <td class="py-2 text-right font-medium">S/. {{ number_format((float) $it->subtotal, 2) }}</td>
                            <td class="py-2 text-right">
                                <form method="POST" action="{{ route('ordenes.items.destroy', ['orden' => $orden, 'item' => $it]) }}" onsubmit="return confirm('Quitar item?');">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-600 hover:underline">Quitar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-4 text-center text-slate-500">Aun no hay items en esta orden.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <form method="POST" action="{{ route('ordenes.items.store', $orden) }}" class="mt-4 grid gap-3 md:grid-cols-4 items-end">
            @csrf
            <div class="md:col-span-2">
                <label class="label">Agregar del catalogo</label>
                <select class="input" name="catalogo_item_id" required>
                    <option value="">Selecciona</option>
                    @foreach($catalogoItems as $ci)
                        <option value="{{ $ci->id }}">
                            [{{ $ci->tipo->etiqueta() }}] {{ $ci->nombre }} — S/. {{ number_format((float) $ci->precio, 2) }}
                            @if($ci->tipo->value === 'bien') (stock: {{ $ci->stock }}) @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Cantidad</label>
                <input class="input" type="number" name="cantidad" min="1" value="1" required>
            </div>
            <button class="btn-primary">Agregar</button>
        </form>
    </div>

    {{-- Pagos (RF-05) --}}
    <div class="card mt-6">
        <h3 class="font-semibold text-primario mb-3">Pagos</h3>
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr><th class="py-2">Fecha</th><th class="py-2">Metodo</th><th class="py-2">Referencia</th><th class="py-2">Registro</th><th class="py-2 text-right">Monto</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orden->pagos as $pago)
                    <tr>
                        <td class="py-2 text-slate-600">{{ $pago->cobrado_at->format('d/m/Y H:i') }}</td>
                        <td class="py-2">{{ $pago->metodo->etiqueta() }}</td>
                        <td class="py-2 text-slate-600">{{ $pago->referencia ?? '—' }}</td>
                        <td class="py-2 text-slate-600">{{ $pago->usuario?->name ?? '—' }}</td>
                        <td class="py-2 text-right font-medium">S/. {{ number_format((float) $pago->monto, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-center text-slate-500">Sin pagos.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if(! $orden->estaLiquidada() || $orden->saldoPendiente() > 0)
            <form method="POST" action="{{ route('ordenes.pagos.store', $orden) }}" class="mt-4 grid gap-3 md:grid-cols-4 items-end">
                @csrf
                <div>
                    <label class="label">Metodo</label>
                    <select class="input" name="metodo" required>
                        @foreach(\App\Enums\MetodoPago::cases() as $m)
                            <option value="{{ $m->value }}">{{ $m->etiqueta() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Monto</label>
                    <input class="input" type="number" name="monto" step="0.01" min="0.01" required value="{{ $orden->saldoPendiente() }}">
                </div>
                <div>
                    <label class="label">Referencia</label>
                    <input class="input" type="text" name="referencia">
                </div>
                <button class="btn-primary">Registrar pago</button>
            </form>
        @endif
    </div>
@endsection
