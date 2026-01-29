<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserIdioma extends Model
{
    protected $fillable = [

        "user_id",
        "idioma_id"


    ];

/*     protected $filterColumns = [
        'user_id',
        'idioma_id'

    ]; */


    /* REferencia cuando existe el modelo de tabla intermedia */
/*

    public function users_idiomas(): HasMany
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function idiomas_users(): HasMany
    {
        return $this->hasMany(Idioma::class, 'idioma_id');
    } */
}
