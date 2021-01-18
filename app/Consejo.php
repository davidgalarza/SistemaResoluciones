<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consejo extends Model
{
    protected $table = 'consejos';

    protected $fillable = [
        'fecha_consejo',
        'presidente',
        'tipo',
        'estado'
    ];
}
