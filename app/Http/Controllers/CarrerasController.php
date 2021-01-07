<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarrerasController extends Controller
{
    public function index() {
        return view('carreras.index');
    }

    public function create() {
        return view('carreras.nuevo');
    }

    public function editar($id)
    {
        //  $consejo = Consejo::findOrFail($id);
        // $formatos = Formato::get();
        // $resoluciones= Resolucion::where('consejo_id', $id)->get();
        return view('carreras.editar', [
            //'consejo' => $consejo,
            //'formatos' => $formatos,
            //'resoluciones' => $resoluciones
        ]);
    }
}
