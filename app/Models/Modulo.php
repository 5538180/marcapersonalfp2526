<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'ciclo_formativo_id',
        'nombre',
        'codigo',
    ];

    public function docentesModulos(): HasMany
    {
        return $this->hasMany(DocenteModulo::class);
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }
}
