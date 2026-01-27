<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserIdiomaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $usuarioIdioma = parent::toArray($request);
        $usuarioIdioma["usuario"] =  $this->users_idiomas->name;
        $usuarioIdioma["idioma"] =  $this->idiomas_users->english_name;

        return parent::toArray($request);
    }
}

