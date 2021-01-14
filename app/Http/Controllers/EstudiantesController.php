<?php

namespace App\Http\Controllers;
use App\Estudiante;
use App\Carrera;

use Illuminate\Http\Request;

class EstudiantesController extends Controller
{
    
    public function index() {
        return view('estudiantes.index');
    }

    public function obtener(Request $request)
    {
        $data = $request->validate([
            'cedula' => ['required', 'string'],
        ]);


        $estudiante = Estudiante::where('cedula', $data['cedula'])->firstOrFail();
        $carrera = Carrera::findOrFail($estudiante->id);
        $datos = $estudiante->toArray();
        $datos['carreraNombre'] = $carrera-> nombre;

        return json_encode($datos) ;
    }



}
