<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalogo_items', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('tipo', 20);
            $table->string('categoria_equipo', 20);
            $table->decimal('precio', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('stock_minimo')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['tipo', 'categoria_equipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogo_items');
    }
};
