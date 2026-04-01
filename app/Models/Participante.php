<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participante extends Model
{
    protected $fillable = [
        'cooperacion_id',
        'user_id',
        'estado',
        'monto_asignado',
    ];

    // Relaciones

    public function cooperacion(): BelongsTo
    {
        return $this->belongsTo(Cooperacion::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Monto que ha pagado este participante en esta cooperación.
     */
    public function montoPagado(): float
    {
        return $this->cooperacion
            ->pagos()
            ->where('user_id', $this->user_id)
            ->where('estado', 'confirmado')
            ->sum('monto');
    }

    /**
     * Recalcula y actualiza el estado según los pagos registrados.
     */

    public function recalcularEstado(): void
    {
        $asignado = $this->monto_asignado ?? $this->cooperacion->monto_por_persona;
        $pagado   = $this->montoPagado();

        if ($pagado <= 0) {
            $estado = 'pendiente';
        } elseif ($pagado >= $asignado) {
            $estado = 'pagado';
        } else {
            $estado = 'parcial';
        }

        $this->update(['estado' => $estado]);
    }
}
