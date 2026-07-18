<?php $__env->startSection('titulo', 'Acceso interno'); ?>

<?php $__env->startSection('contenido'); ?>
    <div class="max-w-md mx-auto">
        <div class="card">
            <h2 class="text-xl font-semibold text-primario mb-4">Acceso interno</h2>

            <?php if($errors->any()): ?>
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="label" for="email">Correo</label>
                    <input class="input" type="email" name="email" id="email" required autofocus value="<?php echo e(old('email')); ?>">
                </div>
                <div>
                    <label class="label" for="password">Contrasena</label>
                    <input class="input" type="password" name="password" id="password" required>
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-secundario focus:ring-acento">
                    Recordarme
                </label>
                <button class="btn-primary w-full">Ingresar</button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>