<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TipoEquipo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

final class Equipo extends Model
{
    /** @use HasFactory<\Database\Factories\EquipoFactory> */
    use HasFactory;

    protected $table = 'equipos';

    /** @var array<int, string> */
    protected $fillable = [
        'cliente_id',
        'tipo',
        'marca',
        'modelo',
        'serie_imei',
        'estado_cosmetico',
        'falla_reportada',
        'password_desbloqueo',
    ];

    /** @var array<int, string> */
    protected $hidden = [
        'password_desbloqueo',
    ];

    protected function casts(): array
    {
        return [
            'tipo' => TipoEquipo::class,
        ];
    }

    // Encripta al escribir y desencripta al leer. Nunca se expone en la vista publica (RNF-04).
    protected function passwordDesbloqueo(): Attribute
    {
        return Attribute::make(
            get: static fn (?string $value): ?string => $value === null || $value === '' ? null : Crypt::decryptString($value),
            set: static fn (?string $value): ?string => $value === null || $value === '' ? null : Crypt::encryptString($value),
        );
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function ordenes(): HasMany
    {
        return $this->hasMany(OrdenTrabajo::class);
    }
}
