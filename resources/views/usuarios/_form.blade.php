@csrf
<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="label">Nombre *</label>
        <input class="input" name="name" required value="{{ old('name', $usuario->name ?? '') }}">
    </div>
    <div>
        <label class="label">Correo *</label>
        <input class="input" type="email" name="email" required value="{{ old('email', $usuario->email ?? '') }}">
    </div>
    <div>
        <label class="label">{{ isset($usuario) ? 'Nueva contrasena (opcional)' : 'Contrasena *' }}</label>
        <input class="input" type="password" name="password" @if(! isset($usuario)) required @endif minlength="8">
    </div>
    <div>
        <label class="label">Rol *</label>
        <select class="input" name="rol" required>
            @foreach(\App\Enums\Rol::cases() as $r)
                <option value="{{ $r->value }}" @selected(old('rol', $usuario->rol->value ?? '') === $r->value)>{{ $r->etiqueta() }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex items-end gap-2">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="activo" value="1" @checked(old('activo', $usuario->activo ?? true))>
            Activo
        </label>
    </div>
</div>
