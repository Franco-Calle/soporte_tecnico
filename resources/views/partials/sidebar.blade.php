<aside class="lg:w-56 bg-primario text-white">
    <div class="px-4 py-4 border-b border-white/10">
        <p class="text-xs uppercase tracking-wider text-white/60">Taller</p>
        <p class="font-semibold">Soporte Tecnico</p>
    </div>
    <nav class="p-2 space-y-1 text-sm">
        @php
            $links = [
                ['dashboard', 'Panel', ['tecnico', 'admin']],
                ['ordenes.index', 'Ordenes de Trabajo', ['tecnico', 'admin']],
                ['clientes.index', 'Clientes', ['tecnico', 'admin']],
                ['catalogo.index', 'Catalogo', ['tecnico', 'admin']],
                ['inventario.index', 'Inventario', ['tecnico', 'admin']],
                ['caja.index', 'Caja Diaria', ['tecnico', 'admin']],
                ['usuarios.index', 'Usuarios', ['admin']],
            ];
        @endphp
        @foreach($links as [$ruta, $texto, $rolesPermitidos])
            @if(in_array(auth()->user()->rol->value, $rolesPermitidos, true))
                <a href="{{ route($ruta) }}"
                   class="block rounded-md px-3 py-2 hover:bg-secundario transition
                          {{ request()->routeIs($ruta) ? 'bg-secundario' : '' }}">
                    {{ $texto }}
                </a>
            @endif
        @endforeach
    </nav>
</aside>
