<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resolucion extends Model
{
    protected $table = 'resoluciones';
    protected $fillable = [
        'nummero_resolucion',
        'estudiante_id',
        'consejo_id',
        'formato_id',
        'usuario_id',
        'respuestas'
    ];
}
