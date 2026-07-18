@extends('layouts.app')

@section('titulo', 'Registrar nueva orden')

@section('contenido')
    <form method="POST" action="{{ route('ordenes.store') }}" class="space-y-6">
        @csrf

        <div class="card">
            <h3 class="font-semibold text-primario mb-3">Datos del cliente</h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="label" for="cliente_dni">DNI *</label>
                    <input class="input" type="text" name="cliente_dni" id="cliente_dni" required value="{{ old('cliente_dni') }}">
                </div>
                <div>
                    <label class="label" for="cliente_nombre">Nombre *</label>
                    <input class="input" type="text" name="cliente_nombre" id="cliente_nombre" required value="{{ old('cliente_nombre') }}">
                </div>
                <div>
                    <label class="label" for="cliente_telefono">Telefono</label>
                    <input class="input" type="text" name="cliente_telefono" id="cliente_telefono" value="{{ old('cliente_telefono') }}">
                </div>
                <div>
                    <label class="label" for="cliente_direccion">Direccion</label>
                    <input class="input" type="text" name="cliente_direccion" id="cliente_direccion" value="{{ old('cliente_direccion') }}">
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="font-semibold text-primario mb-3">Datos del equipo</h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="label" for="tipo">Tipo *</label>
                    <select class="input" name="tipo" id="tipo" required>
                        <option value="">Selecciona</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t->value }}" @selected(old('tipo') === $t->value)>{{ $t->etiqueta() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="marca">Marca *</label>
                    <input class="input" type="text" name="marca" id="marca" required value="{{ old('marca') }}">
                </div>
                <div>
                    <label class="label" for="modelo">Modelo *</label>
                    <input class="input" type="text" name="modelo" id="modelo" required value="{{ old('modelo') }}">
                </div>
                <div>
                    <label class="label" for="serie_imei">Numero de Serie / IMEI</label>
                    <input class="input" type="text" name="serie_imei" id="serie_imei" value="{{ old('serie_imei') }}">
                </div>
                <div class="md:col-span-2">
                    <label class="label" for="estado_cosmetico">Estado cosmetico inicial *</label>
                    <textarea class="input" name="estado_cosmetico" id="estado_cosmetico" rows="2" required>{{ old('estado_cosmetico') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="label" for="falla_reportada">Falla reportada por el cliente *</label>
                    <textarea class="input" name="falla_reportada" id="falla_reportada" rows="3" required>{{ old('falla_reportada') }}</textarea>
                </div>
                <div>
                    <label class="label" for="password_desbloqueo">Contrasena de desbloqueo (opcional)</label>
                    <input class="input" type="text" name="password_desbloqueo" id="password_desbloqueo" value="{{ old('password_desbloqueo') }}">
                    <p class="text-xs text-slate-500 mt-1">Se guarda cifrada. Nunca se muestra en la consulta publica.</p>
                </div>
                <div>
                    <label class="label" for="tecnico_id">Tecnico asignado (opcional)</label>
                    <select class="input" name="tecnico_id" id="tecnico_id">
                        <option value="">Sin asignar</option>
                        @foreach($tecnicos as $t)
                            <option value="{{ $t->id }}" @selected(old('tecnico_id') == $t->id)>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('ordenes.index') }}" class="btn-secondary">Cancelar</a>
            <button class="btn-primary">Guardar orden</button>
        </div>
    </form>
@endsection
