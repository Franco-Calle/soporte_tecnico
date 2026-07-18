<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TipoEquipo;
use App\Models\Cliente;
use App\Models\Equipo;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Equipo> */
final class EquipoFactory extends Factory
{
    protected $model = Equipo::class;

    public function definition(): array
    {
        $tipo = fake()->randomElement(TipoEquipo::cases());

        return [
            'cliente_id' => Cliente::factory(),
            'tipo' => $tipo,
            'marca' => fake()->randomElement(['HP', 'Dell', 'Lenovo', 'Samsung', 'Xiaomi', 'Apple']),
            'modelo' => strtoupper(fake()->bothify('??-####')),
            'serie_imei' => strtoupper(fake()->bothify('SN########')),
            'estado_cosmetico' => fake()->randomElement([
                'Sin ralladuras visibles',
                'Rayones menores en la tapa',
                'Pantalla con astillado en la esquina',
                'Golpe leve en la carcasa',
            ]),
            'falla_reportada' => fake()->randomElement([
                'No enciende',
                'Bateria no dura',
                'Pantalla no responde al tacto',
                'Se apaga solo',
                'Muy lento al iniciar Windows',
            ]),
            'password_desbloqueo' => fake()->boolean(60) ? (string) fake()->numerify('####') : null,
        ];
    }
}
