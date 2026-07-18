<?php if(session('status')): ?>
    <div class="mb-4 rounded-md border border-exito bg-fondo-suave px-4 py-2 text-primario text-sm">
        <?php echo e(session('status')); ?>

    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
        <ul class="list-disc list-inside">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/partials/alerts.blade.php ENDPATH**/ ?>