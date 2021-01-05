<?php

namespace App\Http\Controllers;
use App\Formato;
use App\Carrera;
use App\Http\Controllers\DocumentoJSON;
use App\Rules\DocumentoValido;

use Illuminate\Http\Request;

class FormatosController extends Controller
{
    public function index(){

        $formatos = Formato::get();

        return view('formatos.index', ['formatos' => $formatos]);
    }

    public function create() {
        $carreras = Carrera::get();
        return view('formatos.create', ['carreras' => $carreras]);
    }

    public function store(Request $request){
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:50'],
            'descripcion' => ['required', 'string', 'max:100'],
            'carrera_id' => ['required', 'numeric'],
        ]);


        $formato = Formato::create($data);

        return redirect('/formatos/'.$formato->id.'/editar')->with('success', 'Formato creado.');
    }

    public function editar($id)
    {
        $formato = Formato::findOrFail($id);
    

        return view('formatos.edit', [
            'formato' => $formato
        ]);
    }

    public function update(Request $request, $id){
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:50'],
            'descripcion' => ['required', 'string', 'max:100'],
            'estado'=> ['required','in:BORRADOR,PUBLICO,ELIMINADO']
        ]);

        $formato = Formato::findOrFail($id);
        $formato->nombre = $data['nombre'];
        $formato->descripcion = $data['descripcion'];
        $formato->estado = $data['estado'];
        $formato->save();

        return redirect('/formatos/'.$formato->id.'/editar')->with('success', 'Informacion actulizada');
    }


    public function actualizarPlantilla(Request $request, $id){

        $data = $request->validate([
            'form_schema' => ['required', 'string'],
            'ubicacion_plantilla' => ['required', 'string', 'max:500'],
        ]);

        
        $formato = Formato::findOrFail($id);
        $formato->form_schema = $data['form_schema'];
        $formato->ubicacion_plantilla = $data['ubicacion_plantilla'];
        $formato->save();

        return redirect('/formatos/'.$formato->id.'/editar')->with('success', 'Plantilla actualizada');
    }

    public function wordToSchema(Request $request)
    {

        $request->validate([
            'template_file' => ['required','mimes:doc,docx,dotm',' max:2048', new DocumentoValido],
        ]);
  
        $fileName = time().'.'.$request->template_file->extension();  
   
        $request->template_file->move(public_path('uploads'), $fileName);
   
        $formSchema = DocumentoJSON::pasarAJSON(public_path('uploads/'.$fileName));
        

        return back()->with(['form_schema' => $formSchema, 'file_path' => public_path('uploads/'.$fileName), 'file_name' => $request->template_file->getClientOriginalName() ]);
    }

}

