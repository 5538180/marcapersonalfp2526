<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculo extends Model
{

    /* Referencia Curriculo a Usuario 1-1(lo mio pertenece a usuario) */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
