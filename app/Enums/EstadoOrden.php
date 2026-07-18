<?php

declare(strict_types=1);

namespace App\Enums;

enum EstadoOrden: string
{
    case Recibido = 'recibido';
    case EnDiagnostico = 'en_diagnostico';
    case EsperandoRepuesto = 'esperando_repuesto';
    case EnReparacion = 'en_reparacion';
    case ListoParaEntrega = 'listo_para_entrega';
    case Entregado = 'entregado';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Recibido => 'Equipo Recibido',
            self::EnDiagnostico => 'En Diagnostico',
            self::EsperandoRepuesto => 'Esperando Repuesto',
            self::EnReparacion => 'En Reparacion',
            self::ListoParaEntrega => 'Listo para Entrega',
            self::Entregado => 'Entregado',
        };
    }

    public function orden(): int
    {
        return match ($this) {
            self::Recibido => 1,
            self::EnDiagnostico => 2,
            self::EsperandoRepuesto => 3,
            self::EnReparacion => 4,
            self::ListoParaEntrega => 5,
            self::Entregado => 6,
        };
    }

    public function colorBadge(): string
    {
        return match ($this) {
            self::Recibido => 'bg-slate-200 text-primario',
            self::EnDiagnostico => 'bg-fondo-suave text-secundario',
            self::EsperandoRepuesto => 'bg-yellow-100 text-yellow-800',
            self::EnReparacion => 'bg-blue-100 text-secundario',
            self::ListoParaEntrega => 'bg-exito text-primario',
            self::Entregado => 'bg-primario text-white',
        };
    }

    /** @return array<int, self> */
    public static function lineaTiempo(): array
    {
        return [
            self::Recibido,
            self::EnDiagnostico,
            self::EsperandoRepuesto,
            self::EnReparacion,
            self::ListoParaEntrega,
            self::Entregado,
        ];
    }

    /** @return array<int, array{value: string, label: string}> */
    public static function opciones(): array
    {
        return array_map(
            static fn (self $e): array => ['value' => $e->value, 'label' => $e->etiqueta()],
            self::cases(),
        );
    }
}
