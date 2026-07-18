<?php $__env->startSection('titulo', 'Catalogo'); ?>

<?php $__env->startSection('contenido'); ?>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <form method="GET" class="flex flex-wrap gap-2">
            <select name="categoria" class="input w-56">
                <option value="">Todas las categorias</option>
                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->value); ?>" <?php if($categoriaActual === $c->value): echo 'selected'; endif; ?>><?php echo e($c->etiqueta()); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="tipo" class="input w-52">
                <option value="">Servicios y bienes</option>
                <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t->value); ?>" <?php if($tipoActual === $t->value): echo 'selected'; endif; ?>><?php echo e($t->etiqueta()); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="btn-secondary">Filtrar</button>
        </form>
        <?php if(auth()->user()->esAdmin()): ?>
            <a href="<?php echo e(route('catalogo.create')); ?>" class="btn-primary">Nuevo item</a>
        <?php endif; ?>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="py-2">Item</th>
                    <th class="py-2">Categoria</th>
                    <th class="py-2">Tipo</th>
                    <th class="py-2 text-right">Precio</th>
                    <th class="py-2 text-right">Stock</th>
                    <?php if(auth()->user()->esAdmin()): ?><th></th><?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="py-2">
                            <?php echo e($it->nombre); ?>

                            <?php if($it->descripcion): ?><br><span class="text-xs text-slate-500"><?php echo e($it->descripcion); ?></span><?php endif; ?>
                        </td>
                        <td class="py-2"><?php echo e($it->categoria_equipo->etiqueta()); ?></td>
                        <td class="py-2">
                            <span class="badge <?php echo e($it->tipo->value === 'servicio' ? 'badge-info' : 'bg-slate-100 text-secundario'); ?>">
                                <?php echo e($it->tipo->etiqueta()); ?>

                            </span>
                        </td>
                        <td class="py-2 text-right">S/. <?php echo e(number_format((float) $it->precio, 2)); ?></td>
                        <td class="py-2 text-right">
                            <?php if($it->tipo->value === 'bien'): ?>
                                <span class="<?php echo e($it->tieneStockCritico() ? 'text-red-600 font-semibold' : ''); ?>"><?php echo e($it->stock); ?></span>
                            <?php else: ?>
                                <span class="text-slate-400">—</span>
                            <?php endif; ?>
                        </td>
                        <?php if(auth()->user()->esAdmin()): ?>
                            <td class="py-2 text-right">
                                <a href="<?php echo e(route('catalogo.edit', $it)); ?>" class="text-xs text-secundario hover:underline">Editar</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="py-4 text-center text-slate-500">Sin items en el catalogo.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-4"><?php echo e($items->links()); ?></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/catalogo/index.blade.php ENDPATH**/ ?>