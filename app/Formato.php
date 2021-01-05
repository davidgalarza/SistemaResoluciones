<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formato extends Model
{
    protected $table = 'formatos_resoluciones';
    protected $fillable = [
        'nombre',
        'descripcion',
        'carrera_id',
        'estado',
        'ubicacion_plantilla',
        'form_schema'
    ];
}
