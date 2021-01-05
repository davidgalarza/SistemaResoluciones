<?php

namespace App\Http\Controllers;
use App\Estudiante;

use Illuminate\Http\Request;

class EstudiantesController extends Controller
{
    
    public function obtener(Request $request)
    {
        $data = $request->validate([
            'cedula' => ['required', 'string'],
        ]);


        $estudiante = Estudiante::where('cedula', $data['cedula'])->firstOrFail();
        

        return json_encode($estudiante);
    }

}
