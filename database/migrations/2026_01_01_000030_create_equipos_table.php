<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('tipo', 20);
            $table->string('marca');
            $table->string('modelo');
            // Sirve como numero de serie o IMEI segun tipo (RF-02).
            $table->string('serie_imei')->nullable();
            $table->text('estado_cosmetico');
            $table->text('falla_reportada');
            // RNF-04: se guarda cifrado, nunca se muestra en la vista publica.
            $table->text('password_desbloqueo')->nullable();
            $table->timestamps();

            $table->index(['tipo', 'serie_imei']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
