<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocenteModulo extends Model
{
    use HasFactory;

    protected $table = 'docentes_modulos';

    protected $fillable = [
        'user_id',
        'modulo_id',
        'ciclo_formativo_id',
        'nombre',
        'codigo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }
}
