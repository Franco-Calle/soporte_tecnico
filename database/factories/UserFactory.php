<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/** @extends Factory<User> */
final class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::$password ??= Hash::make('password'),
            'rol' => Rol::Tecnico,
            'activo' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): self
    {
        return $this->state(fn (): array => ['rol' => Rol::Admin]);
    }

    public function tecnico(): self
    {
        return $this->state(fn (): array => ['rol' => Rol::Tecnico]);
    }
}
