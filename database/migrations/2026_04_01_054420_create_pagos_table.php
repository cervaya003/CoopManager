<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * REEMPLAZA la migración original 2026_04_01_025822_create_pagos_table.php
     * Agrega columnas: estado, notas, metodo_pago, comprobante
     */
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('cooperacion_id')
                ->constrained('cooperaciones')
                ->cascadeOnDelete();

            $table->decimal('monto', 10, 2);

            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'otro'])->default('efectivo');

            $table->enum('estado', ['pendiente', 'confirmado', 'rechazado'])->default('confirmado');

            $table->string('comprobante')->nullable(); // ruta del archivo subido

            $table->text('notas')->nullable();

            // Quién registró el pago (puede ser admin)
            $table->foreignId('registrado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
