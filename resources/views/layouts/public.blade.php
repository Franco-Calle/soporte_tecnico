<!DOCTYPE html>
<html lang="es" class="h-full bg-fondo-suave">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Consulta') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-primario">
    <header class="bg-primario text-white">
        <div class="mx-auto max-w-4xl px-4 py-4 flex items-center justify-between">
            <a href="{{ route('consulta.formulario') }}" class="text-lg font-semibold">
                {{ config('app.name') }}
            </a>
            <a href="{{ route('login') }}" class="text-sm text-white/80 hover:text-white">
                Acceso interno
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-4 py-8">
        @yield('contenido')
    </main>

</body>
</html>
