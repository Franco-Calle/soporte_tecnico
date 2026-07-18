<?php $__env->startSection('titulo', 'Caja diaria'); ?>

<?php $__env->startSection('contenido'); ?>
    <form method="GET" class="mb-4 flex gap-2 items-end">
        <div>
            <label class="label">Fecha</label>
            <input type="date" name="fecha" value="<?php echo e($fecha->format('Y-m-d')); ?>" class="input">
        </div>
        <button class="btn-secondary">Ver</button>
    </form>

    <div class="grid gap-4 md:grid-cols-5">
        <?php $__currentLoopData = $metodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card">
                <p class="text-xs uppercase tracking-wider text-slate-500"><?php echo e($m->etiqueta()); ?></p>
                <p class="text-xl font-semibold text-primario mt-1">S/. <?php echo e(number_format($totalesPorMetodo[$m->value] ?? 0, 2)); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="card bg-fondo-suave">
            <p class="text-xs uppercase tracking-wider text-primario">Total del dia</p>
            <p class="text-2xl font-bold text-primario mt-1">S/. <?php echo e(number_format($totalDia, 2)); ?></p>
        </div>
    </div>

    <div class="card mt-6">
        <h3 class="font-semibold text-primario mb-3">Detalle de pagos (<?php echo e($fecha->format('d/m/Y')); ?>)</h3>
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">Hora</th>
                    <th class="py-2">OT</th>
                    <th class="py-2">Metodo</th>
                    <th class="py-2">Referencia</th>
                    <th class="py-2">Registrado por</th>
                    <th class="py-2 text-right">Monto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="py-2 text-slate-600"><?php echo e($p->cobrado_at->format('H:i')); ?></td>
                        <td class="py-2">
                            <a href="<?php echo e(route('ordenes.show', $p->orden_trabajo_id)); ?>" class="text-secundario hover:underline"><?php echo e($p->ordenTrabajo?->numero_ot); ?></a>
                        </td>
                        <td class="py-2"><?php echo e($p->metodo->etiqueta()); ?></td>
                        <td class="py-2 text-slate-600"><?php echo e($p->referencia ?? '—'); ?></td>
                        <td class="py-2 text-slate-600"><?php echo e($p->usuario?->name ?? '—'); ?></td>
                        <td class="py-2 text-right font-medium">S/. <?php echo e(number_format((float) $p->monto, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="py-4 text-center text-slate-500">Sin pagos en esta fecha.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/caja/index.blade.php ENDPATH**/ ?>