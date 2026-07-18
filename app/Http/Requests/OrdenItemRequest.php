<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class OrdenItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'catalogo_item_id' => ['required', 'exists:catalogo_items,id'],
            'cantidad' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }
}
