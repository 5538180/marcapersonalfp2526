<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModuloRequest extends FormRequest
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
            'ciclo_formativo_id' => ['required', 'integer', 'min:1'],
            'nombre' => ['required', 'string', 'max:255'],
            'codigo' => ['required', 'string', 'max:20'],
        ];
    }
}
