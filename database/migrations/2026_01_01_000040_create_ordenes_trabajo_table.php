<?php

declare(strict_types=1);

use App\Enums\EstadoOrden;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_trabajo', function (Blueprint $table): void {
            $table->id();
            $table->string('numero_ot', 20)->unique();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->foreignId('tecnico_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('estado', 30)->default(EstadoOrden::Recibido->value);
            $table->text('diagnostico')->nullable();
            $table->text('notas_internas')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('total_pagado', 10, 2)->default(0);
            $table->timestamp('recibido_at')->useCurrent();
            $table->timestamp('entregado_at')->nullable();
            $table->timestamps();

            // RNF-01: indices para busqueda instantanea.
            $table->index('numero_ot', 'ordenes_numero_ot_idx');
            $table->index('estado');
            $table->index('recibido_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_trabajo');
    }
};
