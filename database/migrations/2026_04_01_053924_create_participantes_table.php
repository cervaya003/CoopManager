<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participantes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cooperacion_id')
                ->constrained('cooperaciones')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Estado del participante
            $table->enum('estado', ['pendiente', 'pagado', 'parcial'])->default('pendiente');

            // Monto asignado (puede diferir del monto_por_persona si se personaliza)
            $table->decimal('monto_asignado', 10, 2)->nullable();

            $table->timestamps();

            // Un usuario solo puede estar una vez por cooperación
            $table->unique(['cooperacion_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participantes');
    }
};
