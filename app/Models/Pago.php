<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MetodoPago;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

final class Pago extends Model
{
    /** @use HasFactory<\Database\Factories\PagoFactory> */
    use HasFactory;

    protected $table = 'pagos';

    /** @var array<int, string> */
    protected $fillable = [
        'orden_trabajo_id',
        'registrado_por',
        'metodo',
        'monto',
        'referencia',
        'cobrado_at',
    ];

    protected function casts(): array
    {
        return [
            'metodo' => MetodoPago::class,
            'monto' => 'decimal:2',
            'cobrado_at' => 'datetime',
        ];
    }

    public function ordenTrabajo(): BelongsTo
    {
        return $this->belongsTo(OrdenTrabajo::class, 'orden_trabajo_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function scopeDelDia(Builder $query, ?Carbon $fecha = null): Builder
    {
        $fecha = $fecha ?? Carbon::today();

        return $query->whereBetween('cobrado_at', [
            $fecha->copy()->startOfDay(),
            $fecha->copy()->endOfDay(),
        ]);
    }
}
