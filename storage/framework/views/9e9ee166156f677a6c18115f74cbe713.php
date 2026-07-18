<!DOCTYPE html>
<html lang="es" class="h-full bg-fondo-suave">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('titulo', 'Consulta'); ?> · <?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="h-full font-sans text-primario">
    <header class="bg-primario text-white">
        <div class="mx-auto max-w-4xl px-4 py-4 flex items-center justify-between">
            <a href="<?php echo e(route('consulta.formulario')); ?>" class="text-lg font-semibold">
                <?php echo e(config('app.name')); ?>

            </a>
            <a href="<?php echo e(route('login')); ?>" class="text-sm text-white/80 hover:text-white">
                Acceso interno
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-4 py-8">
        <?php echo $__env->yieldContent('contenido'); ?>
    </main>

    <footer class="text-center text-xs text-slate-600 py-6">
        Consulta publica &middot; solo se muestra informacion no sensible.
    </footer>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/layouts/public.blade.php ENDPATH**/ ?>