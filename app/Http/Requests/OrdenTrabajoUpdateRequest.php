<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EstadoOrden;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class OrdenTrabajoUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'estado' => ['required', Rule::enum(EstadoOrden::class)],
            'tecnico_id' => ['nullable', 'exists:users,id'],
            'diagnostico' => ['nullable', 'string', 'max:2000'],
            'notas_internas' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
