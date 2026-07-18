<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Panel') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-slate-800">
    <div class="min-h-full flex flex-col lg:flex-row">
        @include('partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-primario text-white shadow-sm">
                <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
                    <h1 class="text-lg font-semibold">@yield('titulo', 'Panel')</h1>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="hidden sm:inline">{{ auth()->user()->name }} · {{ auth()->user()->rol->etiqueta() }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-md bg-secundario px-3 py-1 hover:bg-acento transition">Salir</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 mx-auto w-full max-w-7xl px-4 py-6">
                @include('partials.alerts')
                @yield('contenido')
            </main>

            <footer class="border-t border-slate-200 py-4 text-center text-xs text-slate-500">
                MVP Soporte Tecnico &middot; {{ date('Y') }}
            </footer>
        </div>
    </div>
</body>
</html>
