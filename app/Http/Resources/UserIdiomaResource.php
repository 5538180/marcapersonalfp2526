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

        $user = parent::toArray($request); // Creamos una variable que almacena la request en json
        $user["idioma"] = /* new UserResource( */$this->idiomas->english_name/* ) */; // Añadimos una propiedad nueva (idioma) con el idioma usando el metodo magico de user con una onstancia de el para poner el idioma del usuario

        return $user;
    }
}

