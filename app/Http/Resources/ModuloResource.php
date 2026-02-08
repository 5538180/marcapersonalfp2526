<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuloResource extends JsonResource
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
            'ciclo_formativo_id' => $this->ciclo_formativo_id,
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
        ];
    }
}
