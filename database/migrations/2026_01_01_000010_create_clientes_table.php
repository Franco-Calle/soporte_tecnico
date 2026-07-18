<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table): void {
            $table->id();
            $table->string('dni', 15)->unique();
            $table->string('nombre');
            $table->string('telefono', 20)->nullable();
            $table->string('direccion')->nullable();
            $table->timestamps();

            // RNF-01: indice para busqueda instantanea por DNI (<0.5s).
            $table->index('dni', 'clientes_dni_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
