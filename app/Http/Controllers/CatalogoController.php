<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TipoEquipo;
use App\Enums\TipoItem;
use App\Http\Requests\CatalogoItemRequest;
use App\Models\CatalogoItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CatalogoController extends Controller
{
    // RF-01: catalogo hibrido con filtros por tipo de dispositivo y por tipo de item.
    public function index(Request $request): View
    {
        $categoria = $request->query('categoria');
        $tipo = $request->query('tipo');

        $items = CatalogoItem::query()
            ->when($categoria !== null && $categoria !== '', static function ($q) use ($categoria): void {
                $q->porCategoria($categoria);
            })
            ->when($tipo !== null && $tipo !== '', static function ($q) use ($tipo): void {
                $q->where('tipo', $tipo);
            })
            ->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        return view('catalogo.index', [
            'items' => $items,
            'categorias' => TipoEquipo::cases(),
            'tipos' => TipoItem::cases(),
            'categoriaActual' => $categoria,
            'tipoActual' => $tipo,
        ]);
    }

    public function create(): View
    {
        return view('catalogo.create', [
            'categorias' => TipoEquipo::cases(),
            'tipos' => TipoItem::cases(),
        ]);
    }

    public function store(CatalogoItemRequest $request): RedirectResponse
    {
        CatalogoItem::query()->create($request->validated());

        return redirect()->route('catalogo.index')->with('status', 'Item creado.');
    }

    public function edit(CatalogoItem $item): View
    {
        return view('catalogo.edit', [
            'item' => $item,
            'categorias' => TipoEquipo::cases(),
            'tipos' => TipoItem::cases(),
        ]);
    }

    public function update(CatalogoItemRequest $request, CatalogoItem $item): RedirectResponse
    {
        $item->update($request->validated());

        return redirect()->route('catalogo.index')->with('status', 'Item actualizado.');
    }
}
