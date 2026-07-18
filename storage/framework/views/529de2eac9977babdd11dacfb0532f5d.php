<?php $__env->startSection('titulo', 'Ordenes de Trabajo'); ?>

<?php $__env->startSection('contenido'); ?>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Buscar por OT o DNI" class="input w-56">
            <select name="estado" class="input w-52">
                <option value="">Todos los estados</option>
                <?php $__currentLoopData = $estados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($e->value); ?>" <?php if($estadoActual === $e->value): echo 'selected'; endif; ?>><?php echo e($e->etiqueta()); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="btn-secondary">Filtrar</button>
        </form>
        <a href="<?php echo e(route('ordenes.create')); ?>" class="btn-primary">Nueva orden</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">OT</th>
                    <th class="py-2">Cliente / DNI</th>
                    <th class="py-2">Equipo</th>
                    <th class="py-2">Estado</th>
                    <th class="py-2">Tecnico</th>
                    <th class="py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $ordenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orden): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="py-2 font-medium">
                            <a href="<?php echo e(route('ordenes.show', $orden)); ?>" class="text-secundario hover:underline"><?php echo e($orden->numero_ot); ?></a>
                        </td>
                        <td class="py-2">
                            <?php echo e($orden->equipo->cliente->nombre); ?><br>
                            <span class="text-xs text-slate-500">DNI <?php echo e($orden->equipo->cliente->dni); ?></span>
                        </td>
                        <td class="py-2">
                            <?php echo e($orden->equipo->marca); ?> <?php echo e($orden->equipo->modelo); ?><br>
                            <span class="text-xs text-slate-500"><?php echo e($orden->equipo->tipo->etiqueta()); ?></span>
                        </td>
                        <td class="py-2">
                            <span class="badge <?php echo e($orden->estado->colorBadge()); ?>"><?php echo e($orden->estado->etiqueta()); ?></span>
                        </td>
                        <td class="py-2 text-slate-600"><?php echo e($orden->tecnico?->name ?? '—'); ?></td>
                        <td class="py-2 text-right">S/. <?php echo e(number_format((float) $orden->total, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="py-4 text-center text-slate-500">Sin resultados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-4"><?php echo e($ordenes->links()); ?></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/ordenes/index.blade.php ENDPATH**/ ?>