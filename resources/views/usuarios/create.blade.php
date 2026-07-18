@extends('layouts.app')

@section('titulo', 'Nuevo usuario')

@section('contenido')
    <form method="POST" action="{{ route('usuarios.store') }}" class="card space-y-4 max-w-2xl">
        @include('usuarios._form')
        <div class="flex justify-end gap-2">
            <a href="{{ route('usuarios.index') }}" class="btn-secondary">Cancelar</a>
            <button class="btn-primary">Guardar</button>
        </div>
    </form>
@endsection
