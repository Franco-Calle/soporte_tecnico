@csrf
<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="label" for="dni">DNI *</label>
        <input class="input" type="text" name="dni" id="dni" required value="{{ old('dni', $cliente->dni ?? '') }}">
    </div>
    <div>
        <label class="label" for="nombre">Nombre *</label>
        <input class="input" type="text" name="nombre" id="nombre" required value="{{ old('nombre', $cliente->nombre ?? '') }}">
    </div>
    <div>
        <label class="label" for="telefono">Telefono</label>
        <input class="input" type="text" name="telefono" id="telefono" value="{{ old('telefono', $cliente->telefono ?? '') }}">
    </div>
    <div>
        <label class="label" for="direccion">Direccion</label>
        <input class="input" type="text" name="direccion" id="direccion" value="{{ old('direccion', $cliente->direccion ?? '') }}">
    </div>
</div>
