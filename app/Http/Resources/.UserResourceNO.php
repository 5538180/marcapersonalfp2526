<?php

namespace App\Http\Resources;

use App\Models\Idioma;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return array_merge(parent::toArray($request), [
+            'idiomas' => IdiomaResource::collection($this->idiomas),
         ]);


    }
}
