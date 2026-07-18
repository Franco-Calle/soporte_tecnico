@extends('layouts.app')

@section('titulo', 'Catalogo')

@section('contenido')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <form method="GET" class="flex flex-wrap gap-2">
            <select name="categoria" class="input w-56">
                <option value="">Todas las categorias</option>
                @foreach($categorias as $c)
                    <option value="{{ $c->value }}" @selected($categoriaActual === $c->value)>{{ $c->etiqueta() }}</option>
                @endforeach
            </select>
            <select name="tipo" class="input w-52">
                <option value="">Servicios y bienes</option>
                @foreach($tipos as $t)
                    <option value="{{ $t->value }}" @selected($tipoActual === $t->value)>{{ $t->etiqueta() }}</option>
                @endforeach
            </select>
            <button class="btn-secondary">Filtrar</button>
        </form>
        @if(auth()->user()->esAdmin())
            <a href="{{ route('catalogo.create') }}" class="btn-primary">Nuevo item</a>
        @endif
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">Item</th>
                    <th class="py-2">Categoria</th>
                    <th class="py-2">Tipo</th>
                    <th class="py-2 text-right">Precio</th>
                    <th class="py-2 text-right">Stock</th>
                    @if(auth()->user()->esAdmin())<th></th>@endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($items as $it)
                    <tr>
                        <td class="py-2">
                            {{ $it->nombre }}
                            @if($it->descripcion)<br><span class="text-xs text-slate-500">{{ $it->descripcion }}</span>@endif
                        </td>
                        <td class="py-2">{{ $it->categoria_equipo->etiqueta() }}</td>
                        <td class="py-2">
                            <span class="badge {{ $it->tipo->value === 'servicio' ? 'badge-info' : 'bg-slate-100 text-secundario' }}">
                                {{ $it->tipo->etiqueta() }}
                            </span>
                        </td>
                        <td class="py-2 text-right">S/. {{ number_format((float) $it->precio, 2) }}</td>
                        <td class="py-2 text-right">
                            @if($it->tipo->value === 'bien')
                                <span class="{{ $it->tieneStockCritico() ? 'text-red-600 font-semibold' : '' }}">{{ $it->stock }}</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        @if(auth()->user()->esAdmin())
                            <td class="py-2 text-right">
                                <a href="{{ route('catalogo.edit', $it) }}" class="text-xs text-secundario hover:underline">Editar</a>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-4 text-center text-slate-500">Sin items en el catalogo.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $items->links() }}</div>
    </div>
@endsection
