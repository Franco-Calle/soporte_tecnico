@extends('layouts.public')

@section('titulo', 'Consulta de reparacion')

@section('contenido')
    <div class="card max-w-lg mx-auto">
        <h2 class="text-xl font-semibold text-primario mb-2">Consulta el estado de tu reparacion</h2>
        <p class="text-sm text-slate-600 mb-4">Ingresa tu numero de DNI. No necesitas registrarte.</p>

        <form method="POST" action="{{ route('consulta.buscar') }}" class="space-y-4">
            @csrf
            <div>
                <label class="label" for="dni">DNI</label>
                <input class="input" type="text" name="dni" id="dni" required autofocus inputmode="numeric" pattern="[0-9]*" maxlength="15">
            </div>
            <button class="btn-primary w-full">Consultar</button>
        </form>
    </div>
@endsection
