@extends('layouts.app')

@section('titulo', 'Usuarios')

@section('contenido')
    <div class="flex justify-end mb-4">
        <a href="{{ route('usuarios.create') }}" class="btn-primary">Nuevo usuario</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">Nombre</th>
                    <th class="py-2">Correo</th>
                    <th class="py-2">Rol</th>
                    <th class="py-2">Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($usuarios as $u)
                    <tr>
                        <td class="py-2">{{ $u->name }}</td>
                        <td class="py-2 text-slate-600">{{ $u->email }}</td>
                        <td class="py-2">{{ $u->rol->etiqueta() }}</td>
                        <td class="py-2">
                            <span class="badge {{ $u->activo ? 'badge-exito' : 'bg-slate-200 text-slate-600' }}">
                                {{ $u->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="py-2 text-right">
                            <a href="{{ route('usuarios.edit', $u) }}" class="text-xs text-secundario hover:underline">Editar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $usuarios->links() }}</div>
    </div>
@endsection
