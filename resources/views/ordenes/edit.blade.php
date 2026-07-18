@extends('layouts.app')

@section('titulo', 'Actualizar orden ' . $orden->numero_ot)

@section('contenido')
    <form method="POST" action="{{ route('ordenes.update', $orden) }}" class="card space-y-4 max-w-2xl">
        @csrf @method('PUT')

        <div>
            <label class="label">Estado *</label>
            <select class="input" name="estado" required>
                @foreach($estados as $e)
                    <option value="{{ $e->value }}" @selected($orden->estado === $e)>{{ $e->etiqueta() }}</option>
                @endforeach
            </select>
            <p class="text-xs text-slate-500 mt-1">Al pasar a "Listo para Entrega" el sistema recalcula el total (RF-05).</p>
        </div>

        <div>
            <label class="label">Tecnico</label>
            <select class="input" name="tecnico_id">
                <option value="">Sin asignar</option>
                @foreach($tecnicos as $t)
                    <option value="{{ $t->id }}" @selected($orden->tecnico_id == $t->id)>{{ $t->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="label">Diagnostico</label>
            <textarea class="input" name="diagnostico" rows="3">{{ old('diagnostico', $orden->diagnostico) }}</textarea>
        </div>

        <div>
            <label class="label">Notas internas</label>
            <textarea class="input" name="notas_internas" rows="3">{{ old('notas_internas', $orden->notas_internas) }}</textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('ordenes.show', $orden) }}" class="btn-secondary">Cancelar</a>
            <button class="btn-primary">Guardar</button>
        </div>
    </form>
@endsection
