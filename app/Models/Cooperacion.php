<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cooperacion extends Model
{
    protected $table = 'cooperaciones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'monto_objetivo',
        'monto_por_persona',
        'fecha_limite',
        'estado',
        'imagen',
        'es_publica',
        'created_by',
    ];

    protected $casts = [
        'fecha_limite'    => 'date',
        'monto_objetivo'  => 'decimal:2',
        'monto_por_persona' => 'decimal:2',
        'es_publica'      => 'boolean',
    ];

    // Relaciones

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function participantes(): HasMany
    {
        return $this->hasMany(Participante::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Agregados─

    public function totalRecaudado(): float
    {
        return (float) $this->pagos()->where('estado', 'confirmado')->sum('monto');
    }

    public function montoRestante(): float
    {
        return max(0, (float) $this->monto_objetivo - $this->totalRecaudado());
    }

    public function porcentajeAvance(): float
    {
        if ($this->monto_objetivo <= 0) return 0;
        return min(100, round(($this->totalRecaudado() / $this->monto_objetivo) * 100, 1));
    }

    public function totalParticipantes(): int
    {
        return $this->participantes()->count();
    }

    public function participantesPagados(): int
    {
        return $this->participantes()->where('estado', 'pagado')->count();
    }

    public function participantesPendientes(): int
    {
        return $this->participantes()->where('estado', 'pendiente')->count();
    }

    public function participantesParciales(): int
    {
        return $this->participantes()->where('estado', 'parcial')->count();
    }

    public function estaVencida(): bool
    {
        return $this->fecha_limite->isPast() && $this->estado === 'activa';
    }

    /**
     * Actualiza automáticamente el estado de la cooperación según avance.
     */
    public function actualizarEstado(): void
    {
        if ($this->totalRecaudado() >= $this->monto_objetivo) {
            $this->update(['estado' => 'completada']);
        } elseif ($this->fecha_limite->isPast()) {
            $this->update(['estado' => 'vencida']);
        }
    }
}
