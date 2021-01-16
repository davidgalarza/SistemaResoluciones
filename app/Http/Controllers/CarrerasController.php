<?php

namespace App\Http\Controllers;

use App\Carrera;
use App\Http\Requests\CarreraFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarrerasController extends Controller
{
    public function index() {
        $sql = "SELECT * FROM carreras WHERE eliminado = 0"; 
        // $carreras = Carrera::Search('eliminado'->'1');
        // $carreras = Carrera::all();
        $carreras = DB::select($sql);

        //return view('carreras.index');
        return view('carreras.index',['carreras' => $carreras]);
    }

    //Agregar carrera
    public function create() {        
        return view('carreras.nuevo');
    }

    public function store(CarreraFormRequest $request){            
        
        $carrera= new Carrera();
        $carrera->nombre = request('nombre');
        $carrera->save();
        //return view('carreras.nuevo');
        return redirect('/carreras');
    }

    public function edit($id)
    {
        
        return view('carreras.edit', ['carrera' => Carrera::findOrFail($id)]);
    }


    public function update(CarreraFormRequest $request, $id){
        $carrera=  Carrera::findOrFail($id);
        $carrera->nombre = $request->get('nombre');
        $carrera->update();
        //return view('carreras.nuevo');
        return redirect('/carreras');
    }

    public  function destroy($id)
    {
        # code...
        $carrera=Carrera::findOrFail($id);
        //$carrera->delete();

        $carrera->eliminado = 1;
        $carrera->update();
        return redirect('/carreras');
    }

}
