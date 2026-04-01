<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * REEMPLAZA la migración original 2026_04_01_025816_create_cooperacions_table.php
     * Agrega: estado, imagen, es_publica
     */
    public function up(): void
    {
        Schema::create('cooperaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();

            $table->decimal('monto_objetivo', 10, 2);
            $table->decimal('monto_por_persona', 10, 2);

            $table->date('fecha_limite');

            $table->enum('estado', ['activa', 'completada', 'cancelada', 'vencida'])->default('activa');

            $table->string('imagen')->nullable(); // portada de la cooperación

            $table->boolean('es_publica')->default(true); // visible para todos o solo participantes

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperaciones');
    }
};
