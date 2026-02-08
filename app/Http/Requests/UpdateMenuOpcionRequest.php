<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMenuOpcionRequest extends FormRequest
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
        $menuOpcion = $this->route('menuOpcion') ?? $this->route('menu_opcione');
        $roleId = $this->input('role_id', $menuOpcion?->role_id);

        return [
            'nombre' => ['sometimes', 'string', 'max:255'],
            'ruta' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('menu_opciones', 'ruta')
                    ->where(fn ($query) => $query->where('role_id', $roleId))
                    ->ignore($menuOpcion?->id),
            ],
            'orden' => ['sometimes', 'integer', 'min:0'],
            'role_id' => ['sometimes', 'nullable', 'integer', 'exists:roles,id'],
        ];
    }
}
