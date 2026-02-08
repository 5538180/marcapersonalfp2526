<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocenteModuloRequest extends FormRequest
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
        $docenteModulo = $this->route('docenteModulo') ?? $this->route('docentes_modulo');
        $userId = (int) $this->input('user_id', $docenteModulo?->user_id);

        return [
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'modulo_id' => [
                'sometimes',
                'integer',
                'exists:modulos,id',
                Rule::unique('docentes_modulos', 'modulo_id')
                    ->where(fn ($query) => $query->where('user_id', $userId))
                    ->ignore($docenteModulo?->id),
            ],
            'ciclo_formativo_id' => ['sometimes', 'integer', 'min:1'],
            'nombre' => ['sometimes', 'string', 'max:255'],
            'codigo' => ['sometimes', 'string', 'max:20'],
        ];
    }
}
