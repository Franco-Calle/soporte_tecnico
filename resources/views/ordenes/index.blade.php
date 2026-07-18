@extends('layouts.app')

@section('titulo', 'Ordenes de Trabajo')

@section('contenido')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por OT o DNI" class="input w-56">
            <select name="estado" class="input w-52">
                <option value="">Todos los estados</option>
                @foreach($estados as $e)
                    <option value="{{ $e->value }}" @selected($estadoActual === $e->value)>{{ $e->etiqueta() }}</option>
                @endforeach
            </select>
            <button class="btn-secondary">Filtrar</button>
        </form>
        <a href="{{ route('ordenes.create') }}" class="btn-primary">Nueva orden</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">OT</th>
                    <th class="py-2">Cliente / DNI</th>
                    <th class="py-2">Equipo</th>
                    <th class="py-2">Estado</th>
                    <th class="py-2">Tecnico</th>
                    <th class="py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($ordenes as $orden)
                    <tr>
                        <td class="py-2 font-medium">
                            <a href="{{ route('ordenes.show', $orden) }}" class="text-secundario hover:underline">{{ $orden->numero_ot }}</a>
                        </td>
                        <td class="py-2">
                            {{ $orden->equipo->cliente->nombre }}<br>
                            <span class="text-xs text-slate-500">DNI {{ $orden->equipo->cliente->dni }}</span>
                        </td>
                        <td class="py-2">
                            {{ $orden->equipo->marca }} {{ $orden->equipo->modelo }}<br>
                            <span class="text-xs text-slate-500">{{ $orden->equipo->tipo->etiqueta() }}</span>
                        </td>
                        <td class="py-2">
                            <span class="badge {{ $orden->estado->colorBadge() }}">{{ $orden->estado->etiqueta() }}</span>
                        </td>
                        <td class="py-2 text-slate-600">{{ $orden->tecnico?->name ?? '—' }}</td>
                        <td class="py-2 text-right">S/. {{ number_format((float) $orden->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-4 text-center text-slate-500">Sin resultados.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $ordenes->links() }}</div>
    </div>
@endsection
