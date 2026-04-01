<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    //  Roles

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esMiembro(): bool
    {
        return $this->rol === 'miembro';
    }

    //  Relaciones

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function cooperacionesCreadas(): HasMany
    {
        return $this->hasMany(Cooperacion::class, 'created_by');
    }

    public function participaciones(): HasMany
    {
        return $this->hasMany(Participante::class);
    }

    //  Helpers

    public function totalAportado(): float
    {
        return (float) $this->pagos()->where('estado', 'confirmado')->sum('monto');
    }
}
