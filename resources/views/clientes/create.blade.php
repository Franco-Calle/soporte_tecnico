@extends('layouts.app')

@section('titulo', 'Nuevo cliente')

@section('contenido')
    <form method="POST" action="{{ route('clientes.store') }}" class="card space-y-4 max-w-2xl">
        @include('clientes._form')
        <div class="flex justify-end gap-2">
            <a href="{{ route('clientes.index') }}" class="btn-secondary">Cancelar</a>
            <button class="btn-primary">Guardar</button>
        </div>
    </form>
@endsection
