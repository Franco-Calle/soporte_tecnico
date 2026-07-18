@extends('layouts.app')

@section('titulo', 'Cliente: ' . $cliente->nombre)

@section('contenido')
    <div class="card mb-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500">DNI: <span class="font-mono">{{ $cliente->dni }}</span></p>
                <h2 class="text-xl font-semibold text-primario">{{ $cliente->nombre }}</h2>
                <p class="text-sm text-slate-600">Telefono: {{ $cliente->telefono ?? '—' }}</p>
                <p class="text-sm text-slate-600">Direccion: {{ $cliente->direccion ?? '—' }}</p>
            </div>
            <a href="{{ route('clientes.edit', $cliente) }}" class="btn-secondary">Editar</a>
        </div>
    </div>

    <div class="card">
        <h3 class="font-semibold text-primario mb-3">Equipos y ordenes</h3>
        @forelse($cliente->equipos as $eq)
            <div class="border-b border-slate-100 py-3">
                <p class="font-medium">{{ $eq->tipo->etiqueta() }} — {{ $eq->marca }} {{ $eq->modelo }}</p>
                <p class="text-xs text-slate-500">{{ $eq->tipo->identificadorLabel() }}: {{ $eq->serie_imei ?? '—' }}</p>
                <ul class="mt-2 space-y-1 text-sm">
                    @foreach($eq->ordenes as $o)
                        <li>
                            <a href="{{ route('ordenes.show', $o) }}" class="text-secundario hover:underline">{{ $o->numero_ot }}</a>
                            <span class="badge {{ $o->estado->colorBadge() }} ml-2">{{ $o->estado->etiqueta() }}</span>
                            <span class="text-slate-500 text-xs ml-2">{{ $o->recibido_at->format('d/m/Y') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p class="text-sm text-slate-500">Sin equipos registrados.</p>
        @endforelse
    </div>
@endsection
