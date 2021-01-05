<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consejo;
use App\Formato;
use App\Resolucion;

class ConsejosController extends Controller
{
    public function index() {
        return view('consejos.index');
    }

    public function create() {
        return view('consejos.create');
    }

    public function store(Request $request){
        $data = $request->validate([
            'fecha_consejo' => ['required', 'date'],
            'presidente' => ['required', 'string', 'max:50'],
        ]);


        $consejo = Consejo::create($data);

        return redirect('/consejos/'.$consejo->id.'/editar')->with('success', 'Consejo creado.');
    }

    public function editar($id)
    {
        $consejo = Consejo::findOrFail($id);
        $formatos = Formato::get();
        $resoluciones= Resolucion::where('consejo_id', $id)->get();
        return view('consejos.edit', [
            'consejo' => $consejo,
            'formatos' => $formatos,
            'resoluciones' => $resoluciones
        ]);
    }

}
