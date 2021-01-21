<?php

namespace App\Http\Controllers;
use App\Estudiante;
use App\Carrera;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EstudiantesImport;

use Illuminate\Http\Request;

class EstudiantesController extends Controller
{
    
    public function index() {
        $estudiantes = Estudiante::all();
        $carreras = Carrera::all();
        foreach( $estudiantes as $estu){
            $carreraNom = $carreras->where('id',$estu->carrera_id)->first();
            $estu->carrera_id = $carreraNom->nombre;
        }
        return view('estudiantes.index',['estudiantess'=>$estudiantes]);

    }

    public function obtener(Request $request)
    {
        $data = $request->validate([
            'cedula' => ['required', 'string'],
        ]);


        $estudiante = Estudiante::where('cedula', $data['cedula'])->firstOrFail();
        $carrera = Carrera::findOrFail($estudiante->carrera_id);
        $datos = $estudiante->toArray();
        $datos['carreraNombre'] = $carrera->nombre;

        return json_encode($datos) ;
    }

    public function getEstudiante(Request $request, $cedula)
    {
        $estudiante = Estudiante::where('cedula', $cedula)->firstOrFail();
        $carrera = Carrera::findOrFail($estudiante->carrera_id);
        $datos = $estudiante->toArray();
        $datos['carreraNombre'] = $carrera->nombre;

        return json_encode($datos) ;
    }

    public function importExcel(Request $request){
      
        $request->validate([
            'file' => ['required','mimes:xls,xlsx'],
        ]);
        try {
            $file=$request->file('file');
            Excel::import(new EstudiantesImport,$file);
            return back()->with('mensaje','El listado ha sido registrado correctamente!!');
        } catch(\Exception $e){
            $men="Error: Registro Estudiante no es válido";
            if($e->getMessage()=="No query results for model [App\Carrera]."){
               $men="Error: No se encontro una carrera especificada";
            }
            return back()->with('mensaje', $men);
        }
    }


}
