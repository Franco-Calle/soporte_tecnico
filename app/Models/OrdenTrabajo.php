<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EstadoOrden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

final class OrdenTrabajo extends Model
{
    /** @use HasFactory<\Database\Factories\OrdenTrabajoFactory> */
    use HasFactory;

    protected $table = 'ordenes_trabajo';

    /** @var array<int, string> */
    protected $fillable = [
        'numero_ot',
        'equipo_id',
        'tecnico_id',
        'estado',
        'diagnostico',
        'notas_internas',
        'total',
        'total_pagado',
        'recibido_at',
        'entregado_at',
    ];

    protected function casts(): array
    {
        return [
            'estado' => EstadoOrden::class,
            'total' => 'decimal:2',
            'total_pagado' => 'decimal:2',
            'recibido_at' => 'datetime',
            'entregado_at' => 'datetime',
        ];
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function cliente(): HasOneThrough
    {
        return $this->hasOneThrough(
            Cliente::class,
            Equipo::class,
            'id',
            'id',
            'equipo_id',
            'cliente_id',
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrdenItem::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function scopePendientes(Builder $query): Builder
    {
        return $query->whereNotIn('estado', [
            EstadoOrden::Entregado->value,
        ]);
    }

    public function scopePorEstado(Builder $query, EstadoOrden $estado): Builder
    {
        return $query->where('estado', $estado->value);
    }

    public function scopePorNumeroOt(Builder $query, string $numero): Builder
    {
        return $query->where('numero_ot', trim($numero));
    }

    public function saldoPendiente(): float
    {
        return round((float) $this->total - (float) $this->total_pagado, 2);
    }

    public function estaLiquidada(): bool
    {
        return in_array($this->estado, [
            EstadoOrden::ListoParaEntrega,
            EstadoOrden::Entregado,
        ], true);
    }
}
