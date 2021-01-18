<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consejo;
use App\Formato;
use App\Resolucion;
use App\Estudiante;
use Carbon\Carbon;
use App\Notifications\ResolucionCreada;

class ConsejosController extends Controller
{
    public function index() {
        $consejos = Consejo::orderBy('created_at', 'desc')->paginate(5);
        $puedeCrearConsejo = Consejo::where('estado', '=', 'ENPROCESO')->count() === 0;
        Carbon::setUTF8(true);
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
        return view('consejos.index', [
            'consejos' => $consejos,
            'puedeCrearConsejo' => $puedeCrearConsejo
        ]);
    }

    public function create() {
        return view('consejos.create');
    }

    public function store(Request $request){
        $data = $request->validate([
            'fecha_consejo' => ['required', 'date_format:d/m/Y', 'after_or_equal:today'],
            'presidente' => ['required', 'string', 'max:50'],
            'tipo' => ['required', 'string', 'in:Ordinaria,Extraordinaria']
        ], [
            'fecha_consejo.after_or_equal'=> 'El campo fecha consejo debe ser una fecha posterior o igual a hoy.'
        ]);

        $puedeCrearConsejo = Consejo::where('estado', '=', 'ENPROCESO')->count() === 0;
        if($puedeCrearConsejo) {
            $data['fecha_consejo'] = Carbon::createFromFormat('d/m/Y', $data['fecha_consejo'])->toDateString();

            $consejo = Consejo::create($data);

            return redirect('/consejos/'.$consejo->id.'/editar')->with('success', 'Consejo creado.');
        } else {
            return back()->with('error', 'Ya existe un consejo en Proceso');
        }
        
    }


    public function update(Request $request, $id){
        
        $consejo = Consejo::findOrFail($id);
      
        
        $data = $request->validate([
            'presidente' => ['required', 'string', 'max:50'],
            'estado' => ['required', $consejo->estado == 'ENPROCESO' ? 'in:ENPROCESO,FINALIZADO,CANCELADO': 'in:FINALIZADO,CANCELADO'],
            'tipo' => ['required', 'string', 'in:Ordinaria,Extraordinaria']
        ]);

       
        if($consejo->estado == 'ENPROCESO') {
            $consejo->presidente = $data['presidente'];
            $consejo->estado = $data['estado'];
            $consejo->tipo = $data['tipo'];
            $consejo->save();

            if($data['estado'] == 'FINALIZADO') {

                $resoluciones = Resolucion::where('consejo_id', '=', $consejo->id)->get();

                foreach ($resoluciones as $resolucion) {

                    $estudiante = Estudiante::find($resolucion->estudiante_id);

                    \Notification::route('mail', $estudiante->correoUTA)->notify((new ResolucionCreada($resolucion)));
                }
                
            }

            if($data['estado'] == 'CANCELADO') {
                Resolucion::where('consejo_id', '=', $consejo->id)->delete();
            }
            return redirect('/consejos/'.$consejo->id.'/editar')->with('success', 'Consejo Actualizado.');
        } else{
            return redirect('/consejos/'.$consejo->id.'/editar')->with('error', 'No se puede modificar este consejo');
        }
        

       
    }

    public function editar(Request $request, $id)
    {
        $q = $request->q;
        Carbon::setUTF8(true);
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
        $consejo = Consejo::findOrFail($id);
        $formatos = Formato::where('estado', 'PUBLICO')->get();
        $resoluciones= Resolucion::where('consejo_id', $id)->where('respuestas', 'like', '%' . $q . '%')
        ->orderBy('created_at', 'desc')->paginate(7);

        
        return view('consejos.edit', [
            'consejo' => $consejo,
            'formatos' => $formatos,
            'q' => $q,
            'resoluciones' => $resoluciones
        ]);
    }

}
