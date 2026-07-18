<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Rol;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'activo',
    ];

    /** @var array<int, string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'rol' => Rol::class,
            'activo' => 'boolean',
        ];
    }

    public function ordenesAsignadas(): HasMany
    {
        return $this->hasMany(OrdenTrabajo::class, 'tecnico_id');
    }

    public function pagosRegistrados(): HasMany
    {
        return $this->hasMany(Pago::class, 'registrado_por');
    }

    public function esAdmin(): bool
    {
        return $this->rol === Rol::Admin;
    }

    public function esTecnico(): bool
    {
        return $this->rol === Rol::Tecnico;
    }
}
