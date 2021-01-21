<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resolucion;
use App\Carrera;
use App\Formato;

/**
 * Controller estadisticas
 */

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $contador1 = Carrera::select(Resolucion::raw('count(*) as con'))->get();
        $contador2 = Formato::select(Resolucion::raw('count(*) as con'))->get();
        if($contador1[0]->con > 0 && $contador2[0]->con > 0){
            
            $carreras=Formato::select(Resolucion::raw('count(*) as con'), 'carreras.nombre', 'carreras.id')
            ->join('carreras', 'formatos_resoluciones.carrera_id', '=', 'carreras.id')
            ->groupBy('carreras.nombre', 'carreras.id')
            ->orderBy('con', 'desc')->get();

            $formatoInicial= Formato::select('formatos_resoluciones.nombre')
            ->join('carreras', 'formatos_resoluciones.carrera_id', '=', 'carreras.id')
            ->Where('carreras.nombre', '=', $carreras[0]->nombre)->get();

            return view('home', compact('carreras', 'formatoInicial'));
        }else{
            $carreras=[];
            $formatoInicial=[];
            return view('home', compact('carreras', 'formatoInicial'));
        }
        
            
    }

    public function obtenerDatosGrafica($carrera, $formato){
        return  Resolucion::
                    select(Resolucion::raw('count(resoluciones.id) as totalR'), 'consejos.fecha_consejo')
                    ->join('consejos', 'resoluciones.consejo_id', '=', 'consejos.id')
                    ->join('estudiantes', 'resoluciones.estudiante_id', '=', 'estudiantes.id')
                    ->join('carreras', 'estudiantes.carrera_id', '=', 'carreras.id')
                    ->join('formatos_resoluciones', 'resoluciones.formato_id', '=', 'formatos_resoluciones.id')
                    ->Where('carreras.nombre', '=', $carrera)
                    ->Where('formatos_resoluciones.nombre', '=', $formato)
                    ->groupBy('consejos.fecha_consejo')->get();
        
    }

    public function formatosXid($id){
        return Formato::where('carrera_id','=',$id)->get();
    }

    public function Redireccion()
    {

        return redirect('/');
        
            
    }
}
