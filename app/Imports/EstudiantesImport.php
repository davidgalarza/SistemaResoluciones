<?php

namespace App\Imports;

use App\Estudiante;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Carrera;

class EstudiantesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $estudiante=new Estudiante();
        $estudiante = $estudiante->where('cedula', '=', $row[0])->first();
        if($estudiante) {
            $estudiante->update([
                'apellidos'=> $row[1],
                'nombres'=> $row[2],
                'telefono'=> $row[8],
                'correo'=> $row[9],
                'correoUTA'=> $row[10],
                'folio'=> $row[18],
                'matricula'=> $row[17],
                'carrera_id'=> Carrera::where([['nombre', '=', $row[23]],['eliminado','=',0]])->firstOrFail()->id,
            ]);
            return $estudiante;
        } else {
            return new Estudiante([
         
                'cedula'=> $row[0],
                
                'apellidos'=> $row[1],
                'nombres'=> $row[2],
                'telefono'=> $row[8],
                'correo'=> $row[9],
                'correoUTA'=> $row[10],
                'folio'=> $row[18],
                'matricula'=> $row[17],
                'carrera_id'=> Carrera::where([['nombre', '=', $row[23]],['eliminado','=',0]])->firstOrFail()->id,
            ]);
        }

    }
}
