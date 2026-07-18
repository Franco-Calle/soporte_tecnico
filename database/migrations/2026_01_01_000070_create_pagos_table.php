<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('orden_trabajo_id')->constrained('ordenes_trabajo')->cascadeOnDelete();
            $table->foreignId('registrado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('metodo', 20);
            $table->decimal('monto', 10, 2);
            $table->string('referencia')->nullable();
            $table->timestamp('cobrado_at')->useCurrent();
            $table->timestamps();

            $table->index(['cobrado_at', 'metodo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
