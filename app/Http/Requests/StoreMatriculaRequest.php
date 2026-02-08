<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMatriculaRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'modulo_id' => [
                'required',
                'integer',
                'exists:modulos,id',
                Rule::unique('matriculas', 'modulo_id')->where(
                    fn ($query) => $query->where('user_id', $this->input('user_id'))
                ),
            ],
            'ciclo_formativo_id' => ['nullable', 'integer', 'min:1'],
            'nombre' => ['nullable', 'string', 'max:255'],
            'codigo' => ['nullable', 'string', 'max:20'],
        ];
    }
}
