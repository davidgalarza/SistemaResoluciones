<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentoJSON extends Controller
{
    public static function pasarAJSON($filePath)
    {
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($filePath);
        $formSchema = array();
        $unnamedSections = 0;

        

        foreach($phpWord->getVariables() as $var){
            $varText = trim(preg_replace('~\x{00a0}~siu',' ',$var), "\xC2\xA0\n");

            if(DocumentoJSON::startsWith($varText,'[' ) && DocumentoJSON::endsWith($varText,']')){
                // Section title
                $title = str_replace('[', '', $varText); 
                $title = str_replace(']', '', $title); 
                $chunks = explode('|', $title, 2);

                $title = $chunks[0];
                $description = count($chunks) > 1 ? $chunks[1] : '';

                $formSchema[] = array('title' => $title, 'fields' => array(),'description' => $description, 'varText' => $varText
            );

            } else if(count(explode(',', $varText)) > 1){
                // Replacement varaible
                if(count($formSchema) == 0){
                    // if no title for first section
                    $unnamedSections = $unnamedSections + 1;
                    $formSchema[] = array('title' => 'SECCIÓN '.$unnamedSections , 'fields' => array());
                }

                $lastSection = end($formSchema);


                $sections = explode(',', $varText,2);

                $fieldName = trim($sections[0]);
                $fieldType = trim($sections[1]);


                if(count(explode(';', $fieldType)) > 1){

                    $fieldTypeInfo = explode(';', $fieldType);
                    $fieldType = DocumentoJSON::parseType($fieldTypeInfo[0]);

                    $fieldOpV = explode(',', $fieldTypeInfo[1]);

                    $fieldInfo = array('label' => $fieldName, 'type' => $fieldType);
                    $fieldInfo[$fieldInfo['type'] == 'select' ? 'options' : $fieldInfo['type'] == 'tabla' ? 'headers' : 'values'] = array_map('trim', $fieldOpV);

                } else if(count(explode('|', $fieldType)) > 1){
                    $datos = explode('|', $fieldType);
                    $fieldInfo = array('label' => $fieldName, 'type' => DocumentoJSON::parseType($datos[0]), 'default' => $datos[1]);
                } else {
                    $fieldInfo = array('label' => $fieldName, 'type' => DocumentoJSON::parseType($fieldType));
                }

                $fieldInfo['varText'] = $var;
    
                $formSchema[count($formSchema) - 1]['fields'][] = $fieldInfo; 
            }
        }

        // Unir los campos de marcar en solo uno



        foreach ($formSchema as $key => $section) {
            $camposMarcar = array_filter($section['fields'], function ($campo){return $campo['type'] == 'marcar';});
            
            $preguntas = array_map(function ($campo) {return $campo['label'];}, $camposMarcar);
            $preguntas = array_unique($preguntas);
            if(count($preguntas) > 0){
                
                foreach ($preguntas as $keyP => $pregunta) {
                    $indice = null;
                    $respuestas = array_filter($camposMarcar, function ($campo) use ($pregunta) {  return $campo['label'] == $pregunta; });
                    $fieldInfo = array('label' => trim($pregunta), 'type' => 'marcar');

                    foreach ($respuestas as $keyR => $respuesta) {
                        if($indice == null) $indice = $keyR;
                        unset($formSchema[$key]['fields'][$keyR]);
                        $fieldInfo['values'][] = array('value' => trim($respuesta['values'][0]), 'varText' => $respuesta['varText']);
                    }
                    
                    $formSchema[$key]['fields'][$indice] = $fieldInfo;
               }
               ksort($formSchema[$key]['fields']);
            }
        }



        return $formSchema;
    }


    public static function startsWith ($string, $startString) 
    { 
        $len = strlen($startString); 
        return (substr($string, 0, $len) === $startString); 
    } 

    public static function endsWith($string, $endString) 
    { 
        $len = strlen($endString); 
        if ($len == 0) { 
            return true; 
        } 
        return (substr($string, -$len) === $endString); 
    } 

    public static function parseType($type){

        switch($type){
            case 'texto': return 'text';
            case 'número': return 'number';
            case 'correo': return 'email';
            case 'fecha': return 'date';
            case 'mes': return 'selectMonth';
            case 'hora': return 'time';
            case 'múltiple': return 'checkbox';
            case 'única': return 'radio';
            case 'seleccionar': return 'select';
            case 'teléfono': return 'tel';
            case 'semana': return 'week';
            case 'link': return 'url';
            case 'provincia': return'provincia';
            case 'cédula': return 'cédula';
            case 'ruc': return 'ruc';
            case 'año': return 'año';
            case 'tabla': return 'tabla';
            default: 'texto';
        }

    }
}
