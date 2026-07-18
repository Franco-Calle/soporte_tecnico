<?php $__env->startSection('titulo', 'Panel de control'); ?>

<?php $__env->startSection('contenido'); ?>
    <div class="grid gap-4 md:grid-cols-3">
        <div class="card">
            <p class="text-sm text-slate-500">Ingresos del dia</p>
            <p class="text-3xl font-bold text-primario mt-1">S/. <?php echo e(number_format($ingresosDia, 2)); ?></p>
            <a href="<?php echo e(route('caja.index')); ?>" class="mt-2 inline-block text-xs text-secundario hover:underline">Ver caja</a>
        </div>
        <div class="card">
            <p class="text-sm text-slate-500">Ordenes pendientes</p>
            <p class="text-3xl font-bold text-primario mt-1">
                <?php echo e(collect($conteosEstado)->except(['entregado'])->sum()); ?>

            </p>
            <a href="<?php echo e(route('ordenes.index')); ?>" class="mt-2 inline-block text-xs text-secundario hover:underline">Ver ordenes</a>
        </div>
        <div class="card">
            <p class="text-sm text-slate-500">Items con stock bajo</p>
            <p class="text-3xl font-bold <?php echo e($stockBajo->count() > 0 ? 'text-red-600' : 'text-primario'); ?> mt-1">
                <?php echo e($stockBajo->count()); ?>

            </p>
            <a href="<?php echo e(route('inventario.index')); ?>" class="mt-2 inline-block text-xs text-secundario hover:underline">Ver inventario</a>
        </div>
    </div>

    <?php if($stockBajo->isNotEmpty()): ?>
        <div class="mt-6 card border-l-4 border-red-500">
            <h3 class="font-semibold text-primario">Alerta de stock minimo</h3>
            <p class="text-sm text-slate-600 mb-3">Estos repuestos tienen stock igual o menor al minimo definido.</p>
            <ul class="divide-y divide-slate-100">
                <?php $__currentLoopData = $stockBajo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="py-2 flex items-center justify-between text-sm">
                        <span><?php echo e($item->nombre); ?></span>
                        <span class="badge bg-red-100 text-red-700">Stock: <?php echo e($item->stock); ?></span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="mt-6 card">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-primario">Ultimas ordenes pendientes</h3>
            <a href="<?php echo e(route('ordenes.create')); ?>" class="btn-primary text-xs">Nueva orden</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2">OT</th>
                        <th class="py-2">Cliente</th>
                        <th class="py-2">Equipo</th>
                        <th class="py-2">Estado</th>
                        <th class="py-2">Recibido</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $pendientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orden): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-2 font-medium">
                                <a href="<?php echo e(route('ordenes.show', $orden)); ?>" class="text-secundario hover:underline"><?php echo e($orden->numero_ot); ?></a>
                            </td>
                            <td class="py-2"><?php echo e($orden->equipo->cliente->nombre); ?></td>
                            <td class="py-2"><?php echo e($orden->equipo->marca); ?> <?php echo e($orden->equipo->modelo); ?></td>
                            <td class="py-2">
                                <span class="badge <?php echo e($orden->estado->colorBadge()); ?>"><?php echo e($orden->estado->etiqueta()); ?></span>
                            </td>
                            <td class="py-2 text-slate-500"><?php echo e($orden->recibido_at->format('d/m H:i')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="py-4 text-center text-slate-500">Sin ordenes pendientes.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard.blade.php ENDPATH**/ ?>