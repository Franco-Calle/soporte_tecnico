<?php

declare(strict_types=1);

namespace App\Enums;

enum MetodoPago: string
{
    case Efectivo = 'efectivo';
    case Yape = 'yape';
    case Plin = 'plin';
    case Transferencia = 'transferencia';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Efectivo => 'Efectivo',
            self::Yape => 'Yape',
            self::Plin => 'Plin',
            self::Transferencia => 'Transferencia',
        };
    }

    /** @return array<int, array{value: string, label: string}> */
    public static function opciones(): array
    {
        return array_map(
            static fn (self $m): array => ['value' => $m->value, 'label' => $m->etiqueta()],
            self::cases(),
        );
    }
}
