@csrf
<div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="label">Nombre *</label>
        <input class="input" name="nombre" required value="{{ old('nombre', $item->nombre ?? '') }}">
    </div>
    <div class="md:col-span-2">
        <label class="label">Descripcion</label>
        <input class="input" name="descripcion" value="{{ old('descripcion', $item->descripcion ?? '') }}">
    </div>
    <div>
        <label class="label">Tipo *</label>
        <select class="input" name="tipo" required>
            @foreach($tipos as $t)
                <option value="{{ $t->value }}" @selected(old('tipo', $item->tipo->value ?? '') === $t->value)>{{ $t->etiqueta() }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">Categoria de equipo *</label>
        <select class="input" name="categoria_equipo" required>
            @foreach($categorias as $c)
                <option value="{{ $c->value }}" @selected(old('categoria_equipo', $item->categoria_equipo->value ?? '') === $c->value)>{{ $c->etiqueta() }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">Precio *</label>
        <input class="input" type="number" step="0.01" min="0" name="precio" required value="{{ old('precio', $item->precio ?? '') }}">
    </div>
    <div>
        <label class="label">Stock</label>
        <input class="input" type="number" min="0" name="stock" value="{{ old('stock', $item->stock ?? 0) }}">
    </div>
    <div>
        <label class="label">Stock minimo</label>
        <input class="input" type="number" min="0" name="stock_minimo" value="{{ old('stock_minimo', $item->stock_minimo ?? 1) }}">
    </div>
    <div class="flex items-end gap-2">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="activo" value="1" @checked(old('activo', $item->activo ?? true))>
            Activo
        </label>
    </div>
</div>
