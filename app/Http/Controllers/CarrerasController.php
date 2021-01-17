<?php

namespace App\Http\Controllers;

use App\Carrera;
use App\Http\Requests\CarreraFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CarrerasController extends Controller
{
    public function index(Request $request)
    {
        //$sql = "SELECT * FROM carreras WHERE eliminado = 0";
        // $carreras = Carrera::Search('eliminado'->'1');
        // $carreras = Carrera::all();
        // $carreras = DB::select($sql);
        if ($request) {
            # code...            
            $query = trim($request->get('search'));
            $carreras = Carrera::where('nombre', 'LIKE', '%' . $query . '%')
            ->where('eliminado','=',0)
            ->orderBy('id','asc')
            ->paginate(5);
            return view('carreras.index', ['carreras' => $carreras, 'search' => $query]);
        }

        //return view('carreras.index');
        
    }

    //Agregar carrera
    public function create()
    {
        return view('carreras.nuevo');
    }

    public function store(CarreraFormRequest $request)
    {

        $carrera = new Carrera();
        //$sql = "SELECT eliminado FROM carreras WHERE nombre = " . request('nombre');
        try {
            //code...
           $eliminados = Carrera::where('nombre', '=', request('nombre'))
           ->orderBy('id','asc')
           ->get();
           //print_r($eliminados);
           if ($eliminados[0]->eliminado == '1' && count($eliminados)==1) {
            $carrera->nombre = request('nombre');
            $carrera->save();
            return redirect('/carreras')->with('success', 'Carrera creada.');
        } else if ($eliminados[0]->eliminado == '0' || count($eliminados)>1) {

            $messages = [
                'nombre.unique' => 'El nombre de la carrera ya está en uso.',
            ];
            $validator = Validator::make($request->all(), [
                'nombre' => 'unique:carreras',
            ],$messages);
            if ($validator->fails()) {
                return redirect('/carreras/nuevo')
                            ->withErrors($validator)
                            ->withInput();
            }
        }
        } catch (\Throwable $th) {
            # code...   
            $carrera->nombre = request('nombre');
            $carrera->save();    
            //print_r($eliminados);
            return redirect('/carreras')->with('success', 'Carrera creada.'); 
        } 
        
        // $carrera->nombre = request('nombre');
        // $carrera->save();
        //return view('carreras.nuevo');
    }

    public function edit($id)
    {

        return view('carreras.edit', ['carrera' => Carrera::findOrFail($id)]);
    }


    public function update(CarreraFormRequest $request, $id)
    {
        $carrera =  Carrera::findOrFail($id);
        $carrera->nombre = $request->get('nombre');
        $carrera->update();
        //return view('carreras.nuevo');
        return redirect('/carreras');
    }

    public  function destroy($id)
    {
        # code...
        $carrera = Carrera::findOrFail($id);
        //$carrera->delete();

        $carrera->eliminado = 1;
        $carrera->update();
        return redirect('/carreras');
    }
}
