@extends('layouts.app')

@section('titulo', 'Clientes')

@section('contenido')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <form method="GET" class="flex gap-2">
            <input type="text" name="q" value="{{ $termino }}" placeholder="Buscar por DNI o nombre" class="input w-64">
            <button class="btn-secondary">Buscar</button>
        </form>
        <a href="{{ route('clientes.create') }}" class="btn-primary">Nuevo cliente</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">DNI</th>
                    <th class="py-2">Nombre</th>
                    <th class="py-2">Telefono</th>
                    <th class="py-2 text-right">Equipos</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($clientes as $c)
                    <tr>
                        <td class="py-2 font-mono">{{ $c->dni }}</td>
                        <td class="py-2">{{ $c->nombre }}</td>
                        <td class="py-2 text-slate-600">{{ $c->telefono ?? '—' }}</td>
                        <td class="py-2 text-right">{{ $c->equipos_count }}</td>
                        <td class="py-2 text-right">
                            <a href="{{ route('clientes.show', $c) }}" class="text-secundario hover:underline text-xs">Ver</a>
                            <a href="{{ route('clientes.edit', $c) }}" class="text-secundario hover:underline text-xs ml-2">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-center text-slate-500">Sin clientes.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $clientes->links() }}</div>
    </div>
@endsection
