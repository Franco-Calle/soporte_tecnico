<?php

declare(strict_types=1);

namespace App\Enums;

enum TipoEquipo: string
{
    case Escritorio = 'escritorio';
    case Laptop = 'laptop';
    case Celular = 'celular';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Escritorio => 'Computadora de Escritorio',
            self::Laptop => 'Laptop',
            self::Celular => 'Celular',
        };
    }

    public function identificadorLabel(): string
    {
        return $this === self::Celular ? 'IMEI' : 'Numero de Serie';
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
