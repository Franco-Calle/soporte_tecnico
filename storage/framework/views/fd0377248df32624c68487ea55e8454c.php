<?php $__env->startSection('titulo', 'Consulta de reparacion'); ?>

<?php $__env->startSection('contenido'); ?>
    <div class="card max-w-lg mx-auto">
        <h2 class="text-xl font-semibold text-primario mb-2">Consulta el estado de tu reparacion</h2>
        <p class="text-sm text-slate-600 mb-4">Ingresa tu numero de DNI. No necesitas registrarte.</p>

        <form method="POST" action="<?php echo e(route('consulta.buscar')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="label" for="dni">DNI</label>
                <input class="input" type="text" name="dni" id="dni" required autofocus inputmode="numeric" pattern="[0-9]*" maxlength="15">
            </div>
            <button class="btn-primary w-full">Consultar</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/consulta/formulario.blade.php ENDPATH**/ ?>