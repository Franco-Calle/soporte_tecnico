@extends('layouts.app')

@section('titulo', 'Panel de control')

@section('contenido')
    <div class="grid gap-4 md:grid-cols-3">
        <div class="card">
            <p class="text-sm text-slate-500">Ingresos del dia</p>
            <p class="text-3xl font-bold text-primario mt-1">S/. {{ number_format($ingresosDia, 2) }}</p>
            <a href="{{ route('caja.index') }}" class="mt-2 inline-block text-xs text-secundario hover:underline">Ver caja</a>
        </div>
        <div class="card">
            <p class="text-sm text-slate-500">Ordenes pendientes</p>
            <p class="text-3xl font-bold text-primario mt-1">
                {{ collect($conteosEstado)->except(['entregado'])->sum() }}
            </p>
            <a href="{{ route('ordenes.index') }}" class="mt-2 inline-block text-xs text-secundario hover:underline">Ver ordenes</a>
        </div>
        <div class="card">
            <p class="text-sm text-slate-500">Items con stock bajo</p>
            <p class="text-3xl font-bold {{ $stockBajo->count() > 0 ? 'text-red-600' : 'text-primario' }} mt-1">
                {{ $stockBajo->count() }}
            </p>
            <a href="{{ route('inventario.index') }}" class="mt-2 inline-block text-xs text-secundario hover:underline">Ver inventario</a>
        </div>
    </div>

    @if($stockBajo->isNotEmpty())
        <div class="mt-6 card border-l-4 border-red-500">
            <h3 class="font-semibold text-primario">Alerta de stock minimo</h3>
            <p class="text-sm text-slate-600 mb-3">Estos repuestos tienen stock igual o menor al minimo definido.</p>
            <ul class="divide-y divide-slate-100">
                @foreach($stockBajo as $item)
                    <li class="py-2 flex items-center justify-between text-sm">
                        <span>{{ $item->nombre }}</span>
                        <span class="badge bg-red-100 text-red-700">Stock: {{ $item->stock }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-6 card">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-primario">Ultimas ordenes pendientes</h3>
            <a href="{{ route('ordenes.create') }}" class="btn-primary text-xs">Nueva orden</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2">OT</th>
                        <th class="py-2">Cliente</th>
                        <th class="py-2">Equipo</th>
                        <th class="py-2">Estado</th>
                        <th class="py-2">Recibido</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendientes as $orden)
                        <tr>
                            <td class="py-2 font-medium">
                                <a href="{{ route('ordenes.show', $orden) }}" class="text-secundario hover:underline">{{ $orden->numero_ot }}</a>
                            </td>
                            <td class="py-2">{{ $orden->equipo->cliente->nombre }}</td>
                            <td class="py-2">{{ $orden->equipo->marca }} {{ $orden->equipo->modelo }}</td>
                            <td class="py-2">
                                <span class="badge {{ $orden->estado->colorBadge() }}">{{ $orden->estado->etiqueta() }}</span>
                            </td>
                            <td class="py-2 text-slate-500">{{ $orden->recibido_at->format('d/m H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-center text-slate-500">Sin ordenes pendientes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
