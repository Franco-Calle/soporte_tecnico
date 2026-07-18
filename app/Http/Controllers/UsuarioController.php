<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

final class UsuarioController extends Controller
{
    public function index(): View
    {
        $usuarios = User::query()->orderBy('name')->paginate(20);

        return view('usuarios.index', ['usuarios' => $usuarios]);
    }

    public function create(): View
    {
        return view('usuarios.create');
    }

    public function store(UsuarioRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        User::query()->create($data);

        return redirect()->route('usuarios.index')->with('status', 'Usuario creado.');
    }

    public function edit(User $usuario): View
    {
        return view('usuarios.edit', ['usuario' => $usuario]);
    }

    public function update(UsuarioRequest $request, User $usuario): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('status', 'Usuario actualizado.');
    }
}
