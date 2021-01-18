<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Configuraciones;

class ConfiguracionesController extends Controller
{
    public function editar(Request $request)
    {
        $configuraciones = Configuraciones::get();
        
        return view('configuraionies.edit', [
            'configuraciones' => $configuraciones
        ]);
    }

    public function update(Request $request)
    {


        $configuraciones = Configuraciones::get();
        $res = array();
        foreach ($configuraciones as $configuracion) {
            $res[$configuracion->key] =['required', 'string'];
        }
        $data = $request->validate($res);
       

        foreach ($configuraciones as $configuracion) {
            $conf = Configuraciones::where('key', $configuracion->key)->first();
            $conf->value = $data[$configuracion->key];
            $conf->save();
        }

        
        return back()->with('success', 'Configuraciones actualizadas');
    }
}
