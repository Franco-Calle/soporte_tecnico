@extends('layouts.app')

@section('titulo', 'Editar item')

@section('contenido')
    <form method="POST" action="{{ route('catalogo.update', $item) }}" class="card space-y-4 max-w-2xl">
        @method('PUT')
        @include('catalogo._form', ['tipos' => $tipos, 'categorias' => $categorias])
        <div class="flex justify-end gap-2">
            <a href="{{ route('catalogo.index') }}" class="btn-secondary">Cancelar</a>
            <button class="btn-primary">Guardar</button>
        </div>
    </form>
@endsection
