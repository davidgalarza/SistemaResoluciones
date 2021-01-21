<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consejo;
use App\Formato;
use App\Resolucion;
use App\Estudiante;
use Carbon\Carbon;
use App\Carrera;
use App\Notifications\ResolucionCreada;
use Illuminate\Support\Facades\Http;

class ConsejosController extends Controller
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
        'Tipo Sesión'
    ];

    public function index() {
        $consejos = Consejo::orderBy('created_at', 'desc')->paginate(5);
        $puedeCrearConsejo = Consejo::where('estado', '=', 'ENPROCESO')->count() === 0;
        Carbon::setUTF8(true);
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
        return view('consejos.index', [
            'consejos' => $consejos,
            'puedeCrearConsejo' => $puedeCrearConsejo
        ]);
    }

    public function create() {
        return view('consejos.create');
    }

    public function store(Request $request){
        $data = $request->validate([
            'fecha_consejo' => ['required', 'date_format:d/m/Y', 'after_or_equal:yesterday'],
            'presidente' => ['required', 'string', 'max:50'],
            'tipo' => ['required', 'string', 'in:Ordinaria,Extraordinaria']
        ], [
            'fecha_consejo.after_or_equal'=> 'El campo fecha consejo debe ser una fecha posterior o igual a hoy.'
        ]);

        $puedeCrearConsejo = Consejo::where('estado', '=', 'ENPROCESO')->count() === 0;
        if($puedeCrearConsejo) {
            $data['fecha_consejo'] = Carbon::createFromFormat('d/m/Y', $data['fecha_consejo'])->toDateString();

            $consejo = Consejo::create($data);

            return redirect('/consejos/'.$consejo->id.'/editar')->with('success', 'Consejo creado.');
        } else {
            return back()->with('error', 'Ya existe un consejo en Proceso');
        }
        
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

            if($data['estado'] == 'FINALIZADO') {

                $resoluciones = Resolucion::where('consejo_id', '=', $consejo->id)->get();

                $cedulas = array();

                foreach ($resoluciones as $resolucion) {
                    
                    $estudiante = Estudiante::find($resolucion->estudiante_id);
                    $cedulas[] = $estudiante->cedula;
                    \Notification::route('mail', $estudiante->correoUTA)->notify((new ResolucionCreada($resolucion)));
                }

                $cedulasMandar = implode(",", $cedulas);
                try{
                    $response = Http::get('https://us-central1-sistemaresoluciones-d281b.cloudfunctions.net/notificaciones?cedulas='.$cedulasMandar);
                } catch(Exception  $e){
                    
                }
               
            }

            if($data['estado'] == 'CANCELADO') {
                Resolucion::where('consejo_id', '=', $consejo->id)->delete();
            }
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
        ->orderBy('nummero_resolucion', 'desc')->paginate(7);

        
        return view('consejos.edit', [
            'consejo' => $consejo,
            'formatos' => $formatos,
            'q' => $q,
            'resoluciones' => $resoluciones
        ]);
    }



    public function descargarActa(Request $request, $id)
    {

        $consejo = Consejo::findOrFail($id);

        $resoluciones = Resolucion::where('consejo_id','=', $id)->get();

        $plantillaActa = new \PhpOffice\PhpWord\TemplateProcessor(public_path().'/uploads/acta.docx');

        $plantillaActa->cloneBlock('resolución',$resoluciones->count(), true, true);



        $i = 1;
        foreach ($resoluciones as $resolucion) {
            $formato = Formato::findOrFail($resolucion->formato_id);
            $estudiante = Estudiante::findOrFail($resolucion->estudiante_id);
            $carrera = Carrera::findOrFail($estudiante->carrera_id);

            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($formato->ubicacion_plantilla);

            $valoresRemplazar = array();
            $respuestas = json_decode($resolucion['respuestas'], true);

            foreach ($this->validConsts as $cosnt) {
                $constT = preg_replace('~[ .]~', '_', $cosnt);
                if(array_key_exists($constT, $respuestas)){
                    $valoresRemplazar[$cosnt] = $respuestas[$constT];
                }
            }


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
                    }else if($field['type'] == 'tabla') {
                        $tabla = $this->generateDOC('<body>'.$respuestas[preg_replace('~[ .]~', '_', $field['label'])].'</body>');
    
                        $templateProcessor->replaceXmlBlock($field['varText'], $tabla);
                    } else if(array_key_exists(preg_replace('~[ .]~', '_', $field['label']),$respuestas )){
                        if($field['type'] != 'date' ){
                            $valoresRemplazar[$field['varText']] = $respuestas[preg_replace('~[ .]~', '_', $field['label'])];
                            $valoresRemplazar[$field['label']] = $respuestas[preg_replace('~[ .]~', '_', $field['label'])];
                        } else {
                            $fecha2 = Carbon::createFromFormat('d/m/Y', $respuestas[preg_replace('~[ .]~', '_', $field['label'])])->timezone('America/Bogota');
                            $mes2 = $meses[($fecha2->format('n')) - 1];
                            $valoresRemplazar[$field['varText']] = $mes2. ' ' . $fecha2->format('d'). ', ' . $fecha2->format('Y'); ;
                            $valoresRemplazar[$field['label']] = $mes2. ' ' . $fecha2->format('d'). ', ' . $fecha2->format('Y'); ;
                        }
                    } else{
                        $valoresRemplazar[$field['varText']] = '';
                        $valoresRemplazar[$field['label']] = '';
                    }
                }
    
            }
    
            $templateProcessor->setValues($valoresRemplazar);

            $contenido = $templateProcessor->cloneBlock('contenido');

            $codigoResolucion = $resolucion->nummero_resolucion . '-P-CD-FISEI-UTA-'. $fecha->format('Y');
            $plantillaActa->setValue('Código Resolución#'.$i, $codigoResolucion);
            
            $plantillaActa->replaceXmlBlock('res#'.$i, $contenido);
            $i++;
        }
  
        $fechaConsejo = Carbon::parse($consejo->fecha_consejo);
        $mesConsejo = $meses[($fechaConsejo->format('n')) - 1];
        $plantillaActa->setValue('Max Resolución', $resoluciones->last()->nummero_resolucion);
        $plantillaActa->setValue('Min Resolución', $resoluciones->first()->nummero_resolucion);
        $plantillaActa->setValue('Anio Consejo', $fechaConsejo->format('Y'));
        $plantillaActa->setValue('Presidente Consejo', $consejo->presidente);
        $plantillaActa->setValue('Tipo Sesión', strtoupper ($consejo->tipo));
        $plantillaActa->setValue('Fecha Texto Consejo', strtoupper($fechaConsejo->format('d') . ' de ' . $mesConsejo . ' de ' . $fechaConsejo->format('Y')));


        $fileName = 'Acta Consejo '.$fechaConsejo->format('d') . ' de ' . $mesConsejo . ' de ' . $fechaConsejo->format('Y') .'.docx';
        $headers = [
            'Cache-Control' => 'public',
            'Content-Description' => 'Content-Disposition',
            'Content-Disposition' => 'attachment; filename='.$fileName,
            'Content-Transfer-Encoding' => 'binary'
        ];

        return \Response::download($plantillaActa->save("acta.docx"), $fileName,$headers);
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
    
   

}
