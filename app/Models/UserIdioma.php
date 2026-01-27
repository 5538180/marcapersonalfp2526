<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserIdioma extends Model
{
    protected $fillable = [

        "user_id",
        "idioma_id",
        "certificado"

    ];

    protected $filterColumns = [
        'user_id',
        'idioma_id'

    ];


    public function users_idiomas(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_id');
    }

    public function idiomas_users(): BelongsToMany
    {
        return $this->belongsToMany(Idioma::class, 'idioma_id');
    }
}
