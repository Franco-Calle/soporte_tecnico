<?php $__env->startSection('titulo', 'Resultado de consulta'); ?>

<?php $__env->startSection('contenido'); ?>
    <div class="mb-4">
        <a href="<?php echo e(route('consulta.formulario')); ?>" class="text-sm text-secundario hover:underline">&larr; Nueva consulta</a>
    </div>

    <?php if(! $clienteEncontrado): ?>
        <div class="card">
            <h2 class="text-lg font-semibold text-primario">Sin resultados</h2>
            <p class="text-sm text-slate-600 mt-1">No encontramos ordenes asociadas al DNI <strong><?php echo e($dni); ?></strong>. Verifica el numero o acercate al taller.</p>
        </div>
    <?php else: ?>
        <?php if($ordenes->isEmpty()): ?>
            <div class="card">
                <p class="text-sm">Existe el cliente pero no hay ordenes registradas.</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $ordenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orden): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <div class="card">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <p class="text-xs text-slate-500">Orden</p>
                                <p class="font-semibold text-primario"><?php echo e($orden->numero_ot); ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Cliente</p>
                                <p class="font-medium"><?php echo e($orden->equipo->cliente->nombre); ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Equipo</p>
                                <p class="font-medium"><?php echo e($orden->equipo->marca); ?> <?php echo e($orden->equipo->modelo); ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Saldo pendiente</p>
                                <p class="font-semibold text-primario">S/. <?php echo e(number_format($orden->saldoPendiente(), 2)); ?></p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <p class="text-xs uppercase tracking-wider text-slate-500 mb-3">Estado de tu reparacion</p>
                            <ol class="grid grid-cols-2 md:grid-cols-6 gap-2">
                                <?php $__currentLoopData = $lineaTiempo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estadoLinea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $completado = $estadoLinea->orden() <= $orden->estado->orden();
                                        $actual = $estadoLinea === $orden->estado;
                                    ?>
                                    <li class="rounded-md border p-2 text-xs text-center
                                               <?php echo e($actual ? 'border-secundario bg-fondo-suave font-semibold text-primario'
                                                          : ($completado ? 'border-exito bg-exito/40 text-primario'
                                                                         : 'border-slate-200 bg-white text-slate-400')); ?>">
                                        <span class="block text-[10px] uppercase">Paso <?php echo e($estadoLinea->orden()); ?></span>
                                        <span><?php echo e($estadoLinea->etiqueta()); ?></span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ol>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/consulta/resultado.blade.php ENDPATH**/ ?>