@extends('layouts.app')

@section('titulo', 'Editar cliente')

@section('contenido')
    <form method="POST" action="{{ route('clientes.update', $cliente) }}" class="card space-y-4 max-w-2xl">
        @method('PUT')
        @include('clientes._form')
        <div class="flex justify-end gap-2">
            <a href="{{ route('clientes.show', $cliente) }}" class="btn-secondary">Cancelar</a>
            <button class="btn-primary">Guardar</button>
        </div>
    </form>
@endsection
