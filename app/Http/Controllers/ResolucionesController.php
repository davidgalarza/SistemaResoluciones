<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consejo;
use App\Estudiante;
use App\Formato;
use App\Carrera;
use App\Resolucion;
use Carbon\Carbon;

class ResolucionesController extends Controller
{

    public  $validConsts = [
        'Nombres Apellidos Estudiante',
        'Cédula Estudiante',
        'Fecha Resolución',
        'Correo Personal Estudiante',
        'Correo UTA Estudiante',
        'Carrera Estudiante',
        'Telefono Estudiante',
        'Periodo Académico',
        'Matricula Estudiante',
        'Folio Estudiante',
        'Anio Resolución',
        'Presidente Consejo',
        'Número Resolución'
    ];

    public function formulario(Request $request, $idConsejo, $idFormato, $idEstudiante){

        $consejo = Consejo::findOrFail($idConsejo);
        $formato = Formato::findOrFail($idFormato);
        $estudiante = Estudiante::findOrFail($idEstudiante);
        $carrera = Carrera::findOrFail($estudiante->carrera_id);
        return view('resoluciones.formulario', [
            'consejo'=> $consejo,
            'carrera'=> $carrera, 
            'formato'=> $formato,
            'formSchema'=> json_decode($formato['form_schema'], true),
            'estudiante'=> $estudiante
        ]);
    }

    public function editar(Request $request, $idResolucion){
        
        $resolucion = Resolucion::findOrFail($idResolucion);
        $consejo = Consejo::findOrFail($resolucion->consejo_id);
        $formato = Formato::findOrFail($resolucion->formato_id);
        $estudiante = Estudiante::findOrFail($resolucion->estudiante_id);
        $carrera = Carrera::findOrFail($estudiante->carrera_id);

        return view('resoluciones.editar', [
            'resolucion'=> $resolucion,
            'formato' => $formato,
            'carrera'=> $carrera, 
            'consejo' => $consejo,
            'estudiante' => $estudiante ,
            'formSchema'=> json_decode($formato['form_schema'], true),
            'respuestas'=> json_decode($resolucion['respuestas'], true),
        ]);
    }


    public function delete(Request $request, $idResolucion){
        
        $resolucion = Resolucion::findOrFail($idResolucion);
        $resolucion->delete();

        return redirect('/consejos/'.$resolucion->consejo_id.'/editar')->with('success', 'Resolucion eliminada');
    }

    public function anadir(Request $request){
        $data = $request->input();
        unset($data['_token']);

        $resolucion = Resolucion::create([
            'usuario_id' => auth()->user()->id,
            'estudiante_id' => $data['id_estudiante'],
            'formato_id' => $data['id_formato'],
            'respuestas' => json_encode($data),
            'consejo_id' => $data['id_consejo'],
            'nummero_resolucion' => 1,
        ]);


        return back();
    }

    public function update(Request $request, $idResolucion){
  
        $data = $request->input();
        unset($data['_token']);
        $resolucion = Resolucion::findOrFail($idResolucion);
        
        $resolucion->update([
            'usuario_id' => auth()->user()->id,
            'estudiante_id' => $data['id_estudiante'],
            'formato_id' => $data['id_formato'],
            'respuestas' => json_encode($data),
            'consejo_id' => $data['id_consejo'],
            'nummero_resolucion' => 1,
        ]);

        $resolucion->save();


        return redirect('/consejos/'.$data['id_consejo'].'/editar')->with('success', 'Resolucion actulizada');
    }

    public function descargar(Request $request, $id){
        $resolucion = Resolucion::findOrFail($id);
        $consejo = Consejo::findOrFail($resolucion->consejo_id);
        $estudiante = Estudiante::findOrFail($resolucion->estudiante_id);
        $carrera = Carrera::findOrFail($estudiante->carrera_id);
        $formato = Formato::findOrFail($resolucion->formato_id);
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($formato['ubicacion_plantilla']);

        $valoresRemplazar = array();
        $respuestas = json_decode($resolucion['respuestas'],true);


        foreach ($this->validConsts as $cosnt) {
            $constT = preg_replace('~[ .]~', '_', $cosnt);
            if(array_key_exists($constT, $respuestas)){
                $valoresRemplazar[$cosnt] = $respuestas[$constT];
            }
        }

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($resolucion->created_at)->timezone('America/Bogota');
        $mes = $meses[($fecha->format('n')) - 1];


        $valoresRemplazar['Número Resolución'] = $resolucion->nummero_resolucion;
        $valoresRemplazar['Fecha Resolución'] = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');
        // Estudiante

        $valoresRemplazar['Periodo Académico'] = 'ENERO 2021 - JULIO 2021';
        $valoresRemplazar['Presidente Consejo'] = $consejo->presidente;
        
        


        foreach (json_decode($formato['form_schema'], true) as $section){


            $valoresRemplazar[array_key_exists('varText', $section) ? $section['varText'] :'['.$section['title'].']'] = $section['title'];

            foreach ($section['fields'] as $field) {
                if($field['type'] == 'marcar') {
                    foreach ($field['values'] as $key => $value) {
                        if(array_key_exists(preg_replace('~[ .]~', '_', $field['label']),$respuestas )){
                            $res = $respuestas[preg_replace('~[ .]~', '_', $field['label'])];
                            $valoresRemplazar[$value['varText']] =  strpos($res, $value['value']) !== false ?  '☒': '☐';
                        } else{
                            $valoresRemplazar[$value['varText']] = '☐';
                        }
                    }
                } else if(array_key_exists(preg_replace('~[ .]~', '_', $field['label']),$respuestas )){
                    $valoresRemplazar[$field['varText']] = $respuestas[preg_replace('~[ .]~', '_', $field['label'])];
                } else{
                    $valoresRemplazar[$field['varText']] = '';
                }
                
            }

        }

        $templateProcessor->setValues($valoresRemplazar);

       

        $fileName = $resolucion->id.'.docx';
        $headers = [
            'Cache-Control' => 'public',
            'Content-Description' => 'Content-Disposition',
            'Content-Disposition' => 'attachment; filename='.$fileName,
            'Content-Transfer-Encoding' => 'binary'
        ];

        return \Response::download($templateProcessor->save("h.doc"), $fileName,$headers);
        


    }

}
