@extends('layouts.app')

@section('titulo', 'Inventario de repuestos')

@section('contenido')
    @if($stockCritico->isNotEmpty())
        <div class="mb-4 card border-l-4 border-red-500">
            <h3 class="font-semibold text-primario">Alerta de stock minimo (RF-03)</h3>
            <ul class="mt-2 text-sm divide-y divide-slate-100">
                @foreach($stockCritico as $sc)
                    <li class="py-2 flex justify-between">
                        <span>{{ $sc->nombre }}</span>
                        <span class="badge bg-red-100 text-red-700">Stock: {{ $sc->stock }} · minimo: {{ $sc->stock_minimo }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 card overflow-x-auto">
            <h3 class="font-semibold text-primario mb-3">Stock actual</h3>
            <table class="w-full text-sm">
                <thead class="text-left text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2">Repuesto</th>
                        <th class="py-2">Categoria</th>
                        <th class="py-2 text-right">Precio</th>
                        <th class="py-2 text-right">Stock</th>
                        <th class="py-2 text-right">Minimo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($items as $it)
                        <tr>
                            <td class="py-2">{{ $it->nombre }}</td>
                            <td class="py-2">{{ $it->categoria_equipo->etiqueta() }}</td>
                            <td class="py-2 text-right">S/. {{ number_format((float) $it->precio, 2) }}</td>
                            <td class="py-2 text-right {{ $it->tieneStockCritico() ? 'text-red-600 font-semibold' : '' }}">{{ $it->stock }}</td>
                            <td class="py-2 text-right text-slate-500">{{ $it->stock_minimo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3 class="font-semibold text-primario mb-3">Registrar movimiento</h3>
            <form method="POST" action="{{ route('inventario.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="label">Repuesto</label>
                    <select class="input" name="catalogo_item_id" required>
                        <option value="">Selecciona</option>
                        @foreach($items as $it)
                            <option value="{{ $it->id }}">{{ $it->nombre }} (stock: {{ $it->stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Tipo</label>
                    <select class="input" name="tipo" required>
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                        <option value="ajuste">Ajuste (fijar cantidad)</option>
                    </select>
                </div>
                <div>
                    <label class="label">Cantidad</label>
                    <input class="input" type="number" name="cantidad" min="1" required>
                </div>
                <div>
                    <label class="label">Motivo</label>
                    <input class="input" name="motivo" placeholder="Compra a proveedor, ajuste inventario, etc.">
                </div>
                <button class="btn-primary w-full">Registrar</button>
            </form>
        </div>
    </div>

    <div class="card mt-6">
        <h3 class="font-semibold text-primario mb-3">Ultimos movimientos</h3>
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">Fecha</th>
                    <th class="py-2">Repuesto</th>
                    <th class="py-2">Tipo</th>
                    <th class="py-2 text-right">Cant.</th>
                    <th class="py-2">Motivo</th>
                    <th class="py-2">Usuario</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($ultimosMovimientos as $mv)
                    <tr>
                        <td class="py-2 text-slate-600">{{ $mv->created_at->format('d/m H:i') }}</td>
                        <td class="py-2">{{ $mv->item?->nombre ?? '—' }}</td>
                        <td class="py-2">{{ ucfirst($mv->tipo) }}</td>
                        <td class="py-2 text-right">{{ $mv->cantidad }}</td>
                        <td class="py-2 text-slate-600">{{ $mv->motivo ?? '—' }}</td>
                        <td class="py-2 text-slate-600">{{ $mv->usuario?->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-4 text-center text-slate-500">Sin movimientos aun.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
