<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdiomaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     *
     *Devuelve todos los datos a una query en Json
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $idioma = parent::toArray($request); // Creamos una variable que almacena la request en json
      /*   $idioma["idioma_user"]= new UserIdiomaResource($this->users); */ // Creamos una nueva propiedad en el idioma con una instancia nueva de la tabla pivot con el metodo de idioma user que devuelve el idioam del usuario
        return $idioma; // Devolvemos todo
    }
}

