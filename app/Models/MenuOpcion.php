<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuOpcion extends Model
{
    use HasFactory;

    protected $table = 'menu_opciones';

    protected $fillable = [
        'nombre',
        'ruta',
        'orden',
        'role_id',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
