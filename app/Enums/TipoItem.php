<?php

declare(strict_types=1);

namespace App\Enums;

enum TipoItem: string
{
    case Servicio = 'servicio';
    case Bien = 'bien';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Servicio => 'Servicio',
            self::Bien => 'Bien / Repuesto',
        };
    }

    /** @return array<int, array{value: string, label: string}> */
    public static function opciones(): array
    {
        return array_map(
            static fn (self $t): array => ['value' => $t->value, 'label' => $t->etiqueta()],
            self::cases(),
        );
    }
}
