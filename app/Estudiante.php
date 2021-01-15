<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'nombres',
        'apellidos',
        'cedula',
        'carrera_id',
        'correo',
        'correoUTA',
        'folio',
        'matricula',
        'telefono'
    ];
}
