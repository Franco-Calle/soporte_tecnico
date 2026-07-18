@extends('layouts.app')

@section('titulo', 'Caja diaria')

@section('contenido')
    <form method="GET" class="mb-4 flex gap-2 items-end">
        <div>
            <label class="label">Fecha</label>
            <input type="date" name="fecha" value="{{ $fecha->format('Y-m-d') }}" class="input">
        </div>
        <button class="btn-secondary">Ver</button>
    </form>

    <div class="grid gap-4 md:grid-cols-5">
        @foreach($metodos as $m)
            <div class="card">
                <p class="text-xs uppercase tracking-wider text-slate-500">{{ $m->etiqueta() }}</p>
                <p class="text-xl font-semibold text-primario mt-1">S/. {{ number_format($totalesPorMetodo[$m->value] ?? 0, 2) }}</p>
            </div>
        @endforeach
        <div class="card bg-fondo-suave">
            <p class="text-xs uppercase tracking-wider text-primario">Total del dia</p>
            <p class="text-2xl font-bold text-primario mt-1">S/. {{ number_format($totalDia, 2) }}</p>
        </div>
    </div>

    <div class="card mt-6">
        <h3 class="font-semibold text-primario mb-3">Detalle de pagos ({{ $fecha->format('d/m/Y') }})</h3>
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">Hora</th>
                    <th class="py-2">OT</th>
                    <th class="py-2">Metodo</th>
                    <th class="py-2">Referencia</th>
                    <th class="py-2">Registrado por</th>
                    <th class="py-2 text-right">Monto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pagos as $p)
                    <tr>
                        <td class="py-2 text-slate-600">{{ $p->cobrado_at->format('H:i') }}</td>
                        <td class="py-2">
                            <a href="{{ route('ordenes.show', $p->orden_trabajo_id) }}" class="text-secundario hover:underline">{{ $p->ordenTrabajo?->numero_ot }}</a>
                        </td>
                        <td class="py-2">{{ $p->metodo->etiqueta() }}</td>
                        <td class="py-2 text-slate-600">{{ $p->referencia ?? '—' }}</td>
                        <td class="py-2 text-slate-600">{{ $p->usuario?->name ?? '—' }}</td>
                        <td class="py-2 text-right font-medium">S/. {{ number_format((float) $p->monto, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-4 text-center text-slate-500">Sin pagos en esta fecha.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
