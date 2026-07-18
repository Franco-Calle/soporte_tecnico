<?php $__env->startSection('titulo', 'Usuarios'); ?>

<?php $__env->startSection('contenido'); ?>
    <div class="flex justify-end mb-4">
        <a href="<?php echo e(route('usuarios.create')); ?>" class="btn-primary">Nuevo usuario</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">Nombre</th>
                    <th class="py-2">Correo</th>
                    <th class="py-2">Rol</th>
                    <th class="py-2">Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="py-2"><?php echo e($u->name); ?></td>
                        <td class="py-2 text-slate-600"><?php echo e($u->email); ?></td>
                        <td class="py-2"><?php echo e($u->rol->etiqueta()); ?></td>
                        <td class="py-2">
                            <span class="badge <?php echo e($u->activo ? 'badge-exito' : 'bg-slate-200 text-slate-600'); ?>">
                                <?php echo e($u->activo ? 'Activo' : 'Inactivo'); ?>

                            </span>
                        </td>
                        <td class="py-2 text-right">
                            <a href="<?php echo e(route('usuarios.edit', $u)); ?>" class="text-xs text-secundario hover:underline">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="mt-4"><?php echo e($usuarios->links()); ?></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/usuarios/index.blade.php ENDPATH**/ ?>