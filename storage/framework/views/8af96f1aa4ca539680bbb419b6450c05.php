<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('titulo', 'Panel'); ?> · <?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="h-full font-sans text-slate-800">
    <div class="min-h-full flex flex-col lg:flex-row">
        <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-primario text-white shadow-sm">
                <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
                    <h1 class="text-lg font-semibold"><?php echo $__env->yieldContent('titulo', 'Panel'); ?></h1>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="hidden sm:inline"><?php echo e(auth()->user()->name); ?> · <?php echo e(auth()->user()->rol->etiqueta()); ?></span>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button class="rounded-md bg-secundario px-3 py-1 hover:bg-acento transition">Salir</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 mx-auto w-full max-w-7xl px-4 py-6">
                <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->yieldContent('contenido'); ?>
            </main>

            <footer class="border-t border-slate-200 py-4 text-center text-xs text-slate-500">
                MVP Soporte Tecnico &middot; <?php echo e(date('Y')); ?>

            </footer>
        </div>
    </div>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>