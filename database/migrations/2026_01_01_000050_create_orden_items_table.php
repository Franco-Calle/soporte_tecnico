<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('orden_trabajo_id')->constrained('ordenes_trabajo')->cascadeOnDelete();
            $table->foreignId('catalogo_item_id')->constrained('catalogo_items')->restrictOnDelete();
            $table->string('tipo_snapshot', 20);
            $table->string('nombre_snapshot');
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->index(['orden_trabajo_id', 'tipo_snapshot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_items');
    }
};
