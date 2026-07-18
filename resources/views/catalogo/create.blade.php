@extends('layouts.app')

@section('titulo', 'Nuevo item de catalogo')

@section('contenido')
    <form method="POST" action="{{ route('catalogo.store') }}" class="card space-y-4 max-w-2xl">
        @include('catalogo._form', ['tipos' => $tipos, 'categorias' => $categorias])
        <div class="flex justify-end gap-2">
            <a href="{{ route('catalogo.index') }}" class="btn-secondary">Cancelar</a>
            <button class="btn-primary">Guardar</button>
        </div>
    </form>
@endsection
