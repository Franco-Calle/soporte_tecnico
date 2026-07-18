@extends('layouts.public')

@section('titulo', 'Resultado de consulta')

@section('contenido')
    <div class="mb-4">
        <a href="{{ route('consulta.formulario') }}" class="text-sm text-secundario hover:underline">&larr; Nueva consulta</a>
    </div>

    @if(! $clienteEncontrado)
        <div class="card">
            <h2 class="text-lg font-semibold text-primario">Sin resultados</h2>
            <p class="text-sm text-slate-600 mt-1">No encontramos ordenes asociadas al DNI <strong>{{ $dni }}</strong>. Verifica el numero o acercate al taller.</p>
        </div>
    @else
        @if($ordenes->isEmpty())
            <div class="card">
                <p class="text-sm">Existe el cliente pero no hay ordenes registradas.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($ordenes as $orden)
                    {{-- RNF-04: solo nombre, modelo, estado, monto pendiente. Nunca direccion, telefono ni password. --}}
                    <div class="card">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <p class="text-xs text-slate-500">Orden</p>
                                <p class="font-semibold text-primario">{{ $orden->numero_ot }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Cliente</p>
                                <p class="font-medium">{{ $orden->equipo->cliente->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Equipo</p>
                                <p class="font-medium">{{ $orden->equipo->marca }} {{ $orden->equipo->modelo }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Saldo pendiente</p>
                                <p class="font-semibold text-primario">S/. {{ number_format($orden->saldoPendiente(), 2) }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <p class="text-xs uppercase tracking-wider text-slate-500 mb-3">Estado de tu reparacion</p>
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
                                        <span class="block text-[10px] uppercase">Paso {{ $estadoLinea->orden() }}</span>
                                        <span>{{ $estadoLinea->etiqueta() }}</span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
@endsection
