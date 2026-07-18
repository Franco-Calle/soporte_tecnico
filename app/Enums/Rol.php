<?php

declare(strict_types=1);

namespace App\Enums;

enum Rol: string
{
    case Admin = 'admin';
    case Tecnico = 'tecnico';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Tecnico => 'Tecnico',
        };
    }

    /** @return array<int, array{value: string, label: string}> */
    public static function opciones(): array
    {
        return array_map(
            static fn (self $r): array => ['value' => $r->value, 'label' => $r->etiqueta()],
            self::cases(),
        );
    }
}
