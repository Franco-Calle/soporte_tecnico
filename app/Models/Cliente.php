<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

final class Cliente extends Model
{
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory;

    protected $table = 'clientes';

    /** @var array<int, string> */
    protected $fillable = [
        'dni',
        'nombre',
        'telefono',
        'direccion',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class);
    }

    public function ordenes(): HasManyThrough
    {
        return $this->hasManyThrough(OrdenTrabajo::class, Equipo::class);
    }

    public function scopePorDni(Builder $query, string $dni): Builder
    {
        return $query->where('dni', trim($dni));
    }
}
