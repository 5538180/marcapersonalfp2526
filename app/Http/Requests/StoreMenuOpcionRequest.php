<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMenuOpcionRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:255'],
            'ruta' => [
                'required',
                'string',
                'max:255',
                Rule::unique('menu_opciones', 'ruta')->where(
                    fn ($query) => $query->where('role_id', $this->input('role_id'))
                ),
            ],
            'orden' => ['nullable', 'integer', 'min:0'],
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
        ];
    }
}
