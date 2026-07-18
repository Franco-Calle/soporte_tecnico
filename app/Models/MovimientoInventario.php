<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class MovimientoInventario extends Model
{
    /** @use HasFactory<\Database\Factories\MovimientoInventarioFactory> */
    use HasFactory;

    protected $table = 'movimientos_inventario';

    /** @var array<int, string> */
    protected $fillable = [
        'catalogo_item_id',
        'orden_trabajo_id',
        'usuario_id',
        'tipo',
        'cantidad',
        'motivo',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(CatalogoItem::class, 'catalogo_item_id');
    }

    public function ordenTrabajo(): BelongsTo
    {
        return $this->belongsTo(OrdenTrabajo::class, 'orden_trabajo_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
