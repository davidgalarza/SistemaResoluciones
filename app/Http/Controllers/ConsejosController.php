<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consejo;
use App\Formato;
use App\Resolucion;
use Carbon\Carbon;

class ConsejosController extends Controller
{
    public function index() {
        $consejos = Consejo::paginate(5);
        Carbon::setUTF8(true);
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
        return view('consejos.index', [
            'consejos' => $consejos,
        ]);
    }

    public function create() {
        return view('consejos.create');
    }

    public function store(Request $request){
        $data = $request->validate([
            'fecha_consejo' => ['required', 'date'],
            'presidente' => ['required', 'string', 'max:50'],
            'tipo' => ['required', 'string', 'in:Ordinaria,Extraordinaria']
        ]);


        $consejo = Consejo::create($data);

        return redirect('/consejos/'.$consejo->id.'/editar')->with('success', 'Consejo creado.');
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
