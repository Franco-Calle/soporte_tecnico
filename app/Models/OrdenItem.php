<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TipoItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class OrdenItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrdenItemFactory> */
    use HasFactory;

    protected $table = 'orden_items';

    /** @var array<int, string> */
    protected $fillable = [
        'orden_trabajo_id',
        'catalogo_item_id',
        'tipo_snapshot',
        'nombre_snapshot',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'tipo_snapshot' => TipoItem::class,
            'cantidad' => 'integer',
            'precio_unitario' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function ordenTrabajo(): BelongsTo
    {
        return $this->belongsTo(OrdenTrabajo::class, 'orden_trabajo_id');
    }

    public function catalogoItem(): BelongsTo
    {
        return $this->belongsTo(CatalogoItem::class);
    }
}
