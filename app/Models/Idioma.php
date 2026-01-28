<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @version El modelo es el encado de instanciar la clase dando a los atributos y métodos.
 * Empiezanm por mayúscula.
*/
class Idioma extends Model
{

    protected $fillable = [
        "id",
        "alpha2",
        "alpha2",
        "alpha3t",
        "alpha3b",
        "english_name",
        "native_name"
    ];

    public function user(): BelongsToMany
    {
return $this->belongsToMany(User::class, 'users_idiomas', 'user_id', 'idioma_id');
    }
}

