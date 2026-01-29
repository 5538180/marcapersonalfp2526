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

    public function users(): BelongsToMany // Metodo para relaciona N-M en tra las 3 tablas // Clase del modelo a relacionar, tabla pivot, fk tu clase,fk de la clase a relacionar
    {
return $this->belongsToMany(User::class, 'idioma_user', 'idioma_id', 'user_id');
    }
}

