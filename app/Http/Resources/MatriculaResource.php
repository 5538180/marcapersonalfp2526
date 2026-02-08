<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatriculaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'modulo_id' => $this->modulo_id,
            'ciclo_formativo_id' => $this->ciclo_formativo_id,
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
        ];
    }
}
