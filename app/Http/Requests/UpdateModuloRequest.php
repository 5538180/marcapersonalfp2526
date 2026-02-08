<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateModuloRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'ciclo_formativo_id' => ['sometimes', 'integer', 'min:1'],
            'nombre' => ['sometimes', 'string', 'max:255'],
            'codigo' => ['sometimes', 'string', 'max:20'],
        ];
    }
}
