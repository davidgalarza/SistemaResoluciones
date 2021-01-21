<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consejo;
use App\Estudiante;
use App\Formato;
use App\Carrera;
use App\Resolucion;
use Carbon\Carbon;
use App\Configuraciones;

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
        'Número Resolución',
        'Tipo Sesión',
        'Fecha Consejo'
    ];

    public function formulario(Request $request, $idConsejo, $idFormato, $idEstudiante){

        $consejo = Consejo::findOrFail($idConsejo);
        $formato = Formato::findOrFail($idFormato);
        $estudiante = Estudiante::findOrFail($idEstudiante);
        $carrera = Carrera::findOrFail($estudiante->carrera_id);
        $periodo = Configuraciones::where('key', '=', 'PERIODO')->first();

        return view('resoluciones.formulario', [
            'consejo'=> $consejo,
            'carrera'=> $carrera, 
            'formato'=> $formato,
            'formSchema'=> json_decode($formato['form_schema'], true),
            'estudiante'=> $estudiante,
            'periodo' => $periodo['value']
        ]);
    }

    public function editar(Request $request, $idResolucion){
        
        $resolucion = Resolucion::findOrFail($idResolucion);
        $consejo = Consejo::findOrFail($resolucion->consejo_id);
        $formato = Formato::findOrFail($resolucion->formato_id);
        $estudiante = Estudiante::findOrFail($resolucion->estudiante_id);
        $carrera = Carrera::findOrFail($estudiante->carrera_id);
        $periodo = Configuraciones::where('key', '=', 'PERIODO')->first();

        return view('resoluciones.editar', [
            'resolucion'=> $resolucion,
            'formato' => $formato,
            'carrera'=> $carrera, 
            'consejo' => $consejo,
            'estudiante' => $estudiante ,
            'formSchema'=> json_decode($formato['form_schema'], true),
            'respuestas'=> json_decode($resolucion['respuestas'], true),
            'periodo' => $periodo['value']
        ]);
    }


    public function delete(Request $request, $idResolucion){
        
        $resolucion = Resolucion::findOrFail($idResolucion);
        $resolucionesPosteriores = Resolucion::where('created_at', '>', $resolucion->created_at)->get();
        foreach ($resolucionesPosteriores as  $rp) {
            $rp->nummero_resolucion = $rp->nummero_resolucion - 1;
            $rp->save();
        }
        $resolucion->delete();

        return redirect('/consejos/'.$resolucion->consejo_id.'/editar')->with('success', 'Resolución eliminada');
    }

    public function anadir(Request $request){
        $data = $request->input();
        unset($data['_token']);

        
        $ultimaResolucion = Resolucion::whereNotNull('nummero_resolucion')->whereRaw('year(`created_at`) = ?', array(date('Y')))->latest('created_at')->first();
        if($ultimaResolucion != null) {
            $ultimoNumeroResolucion = $ultimaResolucion->nummero_resolucion; 
        } else {
            $ultimoNumeroResolucion = 0;
        }
        
        $resolucion = Resolucion::create([
            'usuario_id' => auth()->user()->id,
            'estudiante_id' => $data['id_estudiante'],
            'formato_id' => $data['id_formato'],
            'respuestas' => json_encode($data),
            'consejo_id' => $data['id_consejo'],
            'nummero_resolucion' => $ultimoNumeroResolucion + 1
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
        ]);

        $resolucion->save();


        return redirect('/consejos/'.$data['id_consejo'].'/editar')->with('success', 'Resolución actualizada');
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
        Carbon::setUTF8(true);
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $dias = array("lunes","martes","miércoles","jueves","viernes","sábado","domingo");
        $fecha = Carbon::parse($resolucion->created_at)->timezone('America/Bogota');
        $mes = $meses[($fecha->format('n')) - 1];

        $fechaConsejo = Carbon::parse($consejo->fecha_consejo);
        $mesConsejo = $meses[($fecha->format('n')) - 1];
        $diaConsejo = $dias[($fechaConsejo->dayOfWeek) - 1];

        $valoresRemplazar['Tipo Sesión'] = $consejo->tipo;
        $valoresRemplazar['Número Resolución'] = $resolucion->nummero_resolucion;
        $valoresRemplazar['Fecha Resolución'] = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');
        $valoresRemplazar['Fecha Consejo'] = $diaConsejo. ' ' . $fechaConsejo->format('d').  ' de ' . $mesConsejo . ' de ' . $fechaConsejo->format('Y');
        $valoresRemplazar['Anio Resolución'] = $fecha->format('Y');
        // Estudiante

        $valoresRemplazar['Presidente Consejo'] = $consejo->presidente;
        
        


        foreach (json_decode($formato['form_schema'], true) as $section){

            $valoresRemplazar[array_key_exists('varText', $section) ? $section['varText'] :'['.$section['title'].']'] = $section['title'];

            foreach ($section['fields'] as $field) {
                if($field['type'] == 'marcar') {
                    foreach ($field['values'] as $key => $value) {
                        if(array_key_exists(preg_replace('~[ .]~', '_', $field['label']),$respuestas )){
                            $res = $respuestas[preg_replace('~[ .]~', '_', $field['label'])];
                            $valoresRemplazar[$value['varText']] =  strpos($res, $value['value']) !== false ?  '☒': '☐';
                            $valoresRemplazar[$field['label']] =  strpos($res, $value['value']) !== false ?  '☒': '☐';
                        } else{
                            $valoresRemplazar[$value['varText']] = '☐';
                            $valoresRemplazar[$field['label']] = '☐';
                        }
                    }
                } else if($field['type'] == 'tabla'){
                    $tabla = $this->generateDOC('<body>'.$respuestas[preg_replace('~[ .]~', '_', $field['label'])].'</body>');

                    $templateProcessor->replaceXmlBlock($field['varText'], $tabla);
                }
                else if(array_key_exists(preg_replace('~[ .]~', '_', $field['label']),$respuestas )){
                    if($field['type'] != 'date' ){
                        $valoresRemplazar[$field['varText']] = $respuestas[preg_replace('~[ .]~', '_', $field['label'])];
                        $valoresRemplazar[$field['label']] = $respuestas[preg_replace('~[ .]~', '_', $field['label'])];
                    } 
                    else {
                        $fecha2 = Carbon::createFromFormat('d/m/Y', $respuestas[preg_replace('~[ .]~', '_', $field['label'])])->timezone('America/Bogota');
                        $mes2 = $meses[($fecha2->format('n')) - 1];
                        $valoresRemplazar[$field['varText']] = $mes2. ' ' . $fecha2->format('d'). ', ' . $fecha2->format('Y');
                        $valoresRemplazar[$field['label']] = $mes2. ' ' . $fecha2->format('d'). ', ' . $fecha2->format('Y'); ;
                    }
                } else{
                    $valoresRemplazar[$field['varText']] = '';
                    $valoresRemplazar[$field['label']] = '';
                }
                
            }

        }

        $templateProcessor->setValues($valoresRemplazar);
        $templateProcessor->setValue('contenido', '');
        $templateProcessor->setValue('/contenido', '');


        



       

        $fileName = 'Resolución '. $resolucion->nummero_resolucion.'-P-CD-FISEI-UTA-'.$fecha->format('Y').'.docx';
        $headers = [
            'Cache-Control' => 'public',
            'Content-Description' => 'Content-Disposition',
            'Content-Disposition' => 'attachment; filename='.$fileName,
            'Content-Transfer-Encoding' => 'binary'
        ];


        return \Response::download($templateProcessor->save("h.doc"), $fileName,$headers);
        

        
    }

    public function generateDOC($html)
    {
        $objPHPWord = new \PhpOffice\PhpWord\PhpWord();
        

        $section = $objPHPWord->addSection();
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, true);
        $objPHPWord->setDefaultFontSize(8);
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($objPHPWord, 'Word2007');
        $fullxml = $objWriter->getWriterPart('Document')->write();
        $tablexml = preg_replace('/^[\s\S]*(<w:tbl\b.*<\/w:tbl>).*/', '$1', $fullxml);
        return $tablexml;
    }

    public function getResoluiones(Request $request, $id){
        $estudiante = Estudiante::where('cedula', $id)->firstOrFail();
        $resoluciones = Resolucion::where('estudiante_id', $estudiante->id)->get();
        $datos = $resoluciones->toArray();
        return json_encode($datos);
    }
}
