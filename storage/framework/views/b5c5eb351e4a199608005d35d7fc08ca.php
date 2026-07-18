<?php $__env->startSection('titulo', 'Clientes'); ?>

<?php $__env->startSection('contenido'); ?>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <form method="GET" class="flex gap-2">
            <input type="text" name="q" value="<?php echo e($termino); ?>" placeholder="Buscar por DNI o nombre" class="input w-64">
            <button class="btn-secondary">Buscar</button>
        </form>
        <a href="<?php echo e(route('clientes.create')); ?>" class="btn-primary">Nuevo cliente</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">DNI</th>
                    <th class="py-2">Nombre</th>
                    <th class="py-2">Telefono</th>
                    <th class="py-2 text-right">Equipos</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="py-2 font-mono"><?php echo e($c->dni); ?></td>
                        <td class="py-2"><?php echo e($c->nombre); ?></td>
                        <td class="py-2 text-slate-600"><?php echo e($c->telefono ?? '—'); ?></td>
                        <td class="py-2 text-right"><?php echo e($c->equipos_count); ?></td>
                        <td class="py-2 text-right">
                            <a href="<?php echo e(route('clientes.show', $c)); ?>" class="text-secundario hover:underline text-xs">Ver</a>
                            <a href="<?php echo e(route('clientes.edit', $c)); ?>" class="text-secundario hover:underline text-xs ml-2">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="py-4 text-center text-slate-500">Sin clientes.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-4"><?php echo e($clientes->links()); ?></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/clientes/index.blade.php ENDPATH**/ ?>