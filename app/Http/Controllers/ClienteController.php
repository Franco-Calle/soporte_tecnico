<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $termino = $request->query('q');

        $clientes = Cliente::query()
            ->when($termino, static function ($q, $t): void {
                $q->where('dni', 'like', "%{$t}%")
                  ->orWhere('nombre', 'like', "%{$t}%");
            })
            ->withCount('equipos')
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('clientes.index', ['clientes' => $clientes, 'termino' => $termino]);
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    public function store(ClienteRequest $request): RedirectResponse
    {
        Cliente::query()->create($request->validated());

        return redirect()->route('clientes.index')
            ->with('status', 'Cliente registrado.');
    }

    public function show(Cliente $cliente): View
    {
        $cliente->load(['equipos.ordenes' => static fn ($q) => $q->orderByDesc('recibido_at')]);

        return view('clientes.show', ['cliente' => $cliente]);
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', ['cliente' => $cliente]);
    }

    public function update(ClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($request->validated());

        return redirect()->route('clientes.show', $cliente)
            ->with('status', 'Cliente actualizado.');
    }
}
