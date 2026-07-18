<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->esAdmin();
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        $usuarioId = $this->route('usuario')?->id;
        $esCreacion = $this->isMethod('POST');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuarioId)],
            'password' => $esCreacion
                ? ['required', 'string', 'min:8']
                : ['nullable', 'string', 'min:8'],
            'rol' => ['required', Rule::enum(Rol::class)],
            'activo' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['activo' => $this->boolean('activo', true)]);
    }
}
