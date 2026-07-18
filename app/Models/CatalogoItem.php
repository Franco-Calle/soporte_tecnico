<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TipoEquipo;
use App\Enums\TipoItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class CatalogoItem extends Model
{
    /** @use HasFactory<\Database\Factories\CatalogoItemFactory> */
    use HasFactory;

    protected $table = 'catalogo_items';

    /** @var array<int, string> */
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'categoria_equipo',
        'precio',
        'stock',
        'stock_minimo',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'tipo' => TipoItem::class,
            'categoria_equipo' => TipoEquipo::class,
            'precio' => 'decimal:2',
            'stock' => 'integer',
            'stock_minimo' => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function ordenItems(): HasMany
    {
        return $this->hasMany(OrdenItem::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopeServicios(Builder $query): Builder
    {
        return $query->where('tipo', TipoItem::Servicio->value);
    }

    public function scopeBienes(Builder $query): Builder
    {
        return $query->where('tipo', TipoItem::Bien->value);
    }

    public function scopePorCategoria(Builder $query, TipoEquipo|string $categoria): Builder
    {
        $valor = $categoria instanceof TipoEquipo ? $categoria->value : $categoria;

        return $query->where('categoria_equipo', $valor);
    }

    public function scopeStockBajo(Builder $query): Builder
    {
        return $query
            ->where('tipo', TipoItem::Bien->value)
            ->whereColumn('stock', '<=', 'stock_minimo');
    }

    // RF-03: alerta discreta cuando el stock cae a 0 o 1.
    public function tieneStockCritico(): bool
    {
        return $this->tipo === TipoItem::Bien && $this->stock <= 1;
    }
}
