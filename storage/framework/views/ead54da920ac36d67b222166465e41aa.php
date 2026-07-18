<?php $__env->startSection('titulo', 'Inventario de repuestos'); ?>

<?php $__env->startSection('contenido'); ?>
    <?php if($stockCritico->isNotEmpty()): ?>
        <div class="mb-4 card border-l-4 border-red-500">
            <h3 class="font-semibold text-primario">Alerta de stock minimo (RF-03)</h3>
            <ul class="mt-2 text-sm divide-y divide-slate-100">
                <?php $__currentLoopData = $stockCritico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="py-2 flex justify-between">
                        <span><?php echo e($sc->nombre); ?></span>
                        <span class="badge bg-red-100 text-red-700">Stock: <?php echo e($sc->stock); ?> · minimo: <?php echo e($sc->stock_minimo); ?></span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 card overflow-x-auto">
            <h3 class="font-semibold text-primario mb-3">Stock actual</h3>
            <table class="w-full text-sm">
                <thead class="text-left text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2">Repuesto</th>
                        <th class="py-2">Categoria</th>
                        <th class="py-2 text-right">Precio</th>
                        <th class="py-2 text-right">Stock</th>
                        <th class="py-2 text-right">Minimo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="py-2"><?php echo e($it->nombre); ?></td>
                            <td class="py-2"><?php echo e($it->categoria_equipo->etiqueta()); ?></td>
                            <td class="py-2 text-right">S/. <?php echo e(number_format((float) $it->precio, 2)); ?></td>
                            <td class="py-2 text-right <?php echo e($it->tieneStockCritico() ? 'text-red-600 font-semibold' : ''); ?>"><?php echo e($it->stock); ?></td>
                            <td class="py-2 text-right text-slate-500"><?php echo e($it->stock_minimo); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3 class="font-semibold text-primario mb-3">Registrar movimiento</h3>
            <form method="POST" action="<?php echo e(route('inventario.store')); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="label">Repuesto</label>
                    <select class="input" name="catalogo_item_id" required>
                        <option value="">Selecciona</option>
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($it->id); ?>"><?php echo e($it->nombre); ?> (stock: <?php echo e($it->stock); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="label">Tipo</label>
                    <select class="input" name="tipo" required>
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                        <option value="ajuste">Ajuste (fijar cantidad)</option>
                    </select>
                </div>
                <div>
                    <label class="label">Cantidad</label>
                    <input class="input" type="number" name="cantidad" min="1" required>
                </div>
                <div>
                    <label class="label">Motivo</label>
                    <input class="input" name="motivo" placeholder="Compra a proveedor, ajuste inventario, etc.">
                </div>
                <button class="btn-primary w-full">Registrar</button>
            </form>
        </div>
    </div>

    <div class="card mt-6">
        <h3 class="font-semibold text-primario mb-3">Ultimos movimientos</h3>
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">Fecha</th>
                    <th class="py-2">Repuesto</th>
                    <th class="py-2">Tipo</th>
                    <th class="py-2 text-right">Cant.</th>
                    <th class="py-2">Motivo</th>
                    <th class="py-2">Usuario</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $ultimosMovimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="py-2 text-slate-600"><?php echo e($mv->created_at->format('d/m H:i')); ?></td>
                        <td class="py-2"><?php echo e($mv->item?->nombre ?? '—'); ?></td>
                        <td class="py-2"><?php echo e(ucfirst($mv->tipo)); ?></td>
                        <td class="py-2 text-right"><?php echo e($mv->cantidad); ?></td>
                        <td class="py-2 text-slate-600"><?php echo e($mv->motivo ?? '—'); ?></td>
                        <td class="py-2 text-slate-600"><?php echo e($mv->usuario?->name ?? '—'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="py-4 text-center text-slate-500">Sin movimientos aun.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/inventario/index.blade.php ENDPATH**/ ?>