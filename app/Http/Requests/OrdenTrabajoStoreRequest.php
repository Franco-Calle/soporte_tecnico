<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\TipoEquipo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class OrdenTrabajoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'cliente_dni' => ['required', 'string', 'max:15'],
            'cliente_nombre' => ['required', 'string', 'max:255'],
            'cliente_telefono' => ['nullable', 'string', 'max:20'],
            'cliente_direccion' => ['nullable', 'string', 'max:255'],

            'tipo' => ['required', Rule::enum(TipoEquipo::class)],
            'marca' => ['required', 'string', 'max:100'],
            'modelo' => ['required', 'string', 'max:100'],
            'serie_imei' => ['nullable', 'string', 'max:100'],
            'estado_cosmetico' => ['required', 'string', 'max:1000'],
            'falla_reportada' => ['required', 'string', 'max:1000'],
            'password_desbloqueo' => ['nullable', 'string', 'max:100'],

            'tecnico_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
