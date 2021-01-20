<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DocumentoValido implements Rule
{
    public $invalid = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validTypes = ['texto', 'número', 'correo', 'fecha', 'mes', 'hora', 'múltiple', 'única', 'seleccionar', 'teléfono', 'semana', 'anio',  'cédula', 'ruc', 'tabla'];
        $validConsts = [
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
            'Fecha Consejo',
            'contenido',
            '/contenido'
        ];
        
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($value->getPathName());
        $vars = $phpWord->getVariables();

        if(count($vars) == 0){
            $this->invalid[] = 'La plantilla no tiene campos';
        }

        foreach($vars as $varia){
            if(count(explode(',', $varia)) > 1){
                $sections = explode(',', $varia,2);
                $validConsts[] = trim($sections[0]);
            }
        }

        foreach($vars as $varia){


            if(count(explode(',', $varia)) > 1){
                $sections = explode(',', $varia,2);
                $this->validConsts[] = trim($sections[0]);
                $type = trim($sections[1]);
                $type = trim(explode('|', explode(';', $type)[0])[0]);
                if(!in_array($type, $validTypes)){
                    $this->invalid[] = 'Tipo <span style="color: #007bff">'.$type.'</span> no valido en ${'.$varia.'}';
                }
                if($type == 'seleccionar' || $type == 'multiple' || $type == 'unica' || $type == 'tabla'){
                    $opS = explode(';', $sections[1]);
                    if(count($opS) > 1){
                        if(count(explode(',', $opS[1]))==0){
                            $this->invalid[] = 'Tipo '.$type.' requiere opciones ${'.$varia.'}';
                        }
                    } else {
                        $this->invalid[] = 'Tipo '.$type.' requiere opciones en ${'.$varia.'}';
                    }
                } else if(count(explode('|', $sections[1]) ) >1){
                    $opS =  explode('|', $sections[1]);
                    if(!isset($opS[1])){
                        $this->invalid[] = 'Tipo '.$type.' requiere valor por defecto en ${'.$varia.'}';
                    }
         
                }

            } else {
                $constName = trim($varia);

                if(!in_array($constName, $validConsts)){
                    $this->invalid[] = 'La constante '.$constName.' no existe en ${'.$varia.'}';
                }
            }
            
        }
        return count($this->invalid) == 0;
    }


    public function startsWith ($string, $startString) 
    { 
        $len = strlen($startString); 
        return (substr($string, 0, $len) === $startString); 
    } 

    public function endsWith($string, $endString) 
    { 
        $len = strlen($endString); 
        if ($len == 0) { 
            return true; 
        } 
        return (substr($string, -$len) === $endString); 
    } 

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Errores en plantilla<li>'.join('</li><li>',$this->invalid);
    }
}
