<?php

namespace App\Http\Controllers;
use App\Formato;
use App\Carrera;
use App\Http\Controllers\DocumentoJSON;
use App\Rules\DocumentoValido;
use App\Rules\WordRule;

use Illuminate\Http\Request;

class FormatosController extends Controller
{
    public function index(Request $request){

        $estado = $request->estado;
        $qu = $request->q;



        $formatos = Formato::where(function($q) use ($qu){
                $q->where('nombre','LIKE', '%'.$qu.'%')
                ->orWhere('descripcion','LIKE', '%'.$qu.'%');
            }
        )->where('estado', 'LIKE', '%'.$estado.'%')->paginate(5);
        $carreras = Carrera::get();

        
        return view('formatos.index', [
            'formatos' => $formatos, 
            'carreras' => $carreras->toArray(),
            'q' => $qu,
            'estado' => $estado]);
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
        $formato = Formato::findOrFail($id);
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:50'],
            'descripcion' => ['required', 'string', 'max:100'],
            'estado'=> ['required', $formato->form_schema == null ? 'in:BORRADOR,ELIMINADO' : 'in:BORRADOR,ELIMINADO,PUBLICO']
        ], ['estado.in' => 'Primero subir la plantilla']);

        
        $formato->nombre = $data['nombre'];
        $formato->descripcion = $data['descripcion'];
        $formato->estado = $data['estado'];
        $formato->save();

        return redirect('/formatos/'.$formato->id.'/editar')->with('success', 'Información actualizada');
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
            'template_file' => ['required','file',new WordRule($request->file('template_file')),'max:5000', new DocumentoValido],
        ]);
  
        $fileName = time().'.'.$request->template_file->extension();  
   
        $request->template_file->move(public_path('uploads'), $fileName);
   
        $formSchema = DocumentoJSON::pasarAJSON(public_path('uploads/'.$fileName));
        

        return back()->with(['form_schema' => $formSchema, 'file_path' => public_path('uploads/'.$fileName), 'file_name' => $request->template_file->getClientOriginalName() ]);
    }

}

