@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Editar resolucion</h3>
                    
                </div>

                <div class="card-body">
                    <form id="template_form" method="POST" action="/resoluciones/{{$resolucion->id}}/actualizar" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}
                        <input type="text" name="id_consejo" value="{{$consejo->id}}" hidden>
                        <input type="text" name="id_formato" value="{{$formato->id}}" hidden>
                        <input type="text" name="id_estudiante" value="{{$estudiante->id}}" hidden>
    


                        <input type="text" name="Nombres Apellidos Estudiante" value="{{$estudiante->nombres}} {{$estudiante->apellidos}}" hidden>
                        <input type="text" name="Cédula Estudiante" value="{{$estudiante->cedula}}" hidden>
                        <input type="text" name="Correo Personal Estudiante" value="{{$estudiante->correo}}" hidden>
                        <input type="text" name="Correo UTA Estudiante" value="{{$estudiante->correoUTA}}" hidden>
                        <input type="text" name="Carrera Estudiante" value="{{$carrera->nombre}}" hidden>
                        <input type="text" name="Telefono Estudiante" value="{{$estudiante->telefono}}" hidden>
                        <input type="text" name="Matricula Estudiante" value="{{$estudiante->matricula}}" hidden>
                        <input type="text" name="Folio Estudiante" value="{{$estudiante->folio}}" hidden>
                        <input type="text" name="Periodo Académico" value="{{$estudiante->folio}}" hidden>

                        @foreach ($formSchema as $section)
                            <div id="{{$section['title']}}" class="{{$section['title']!='SECCIÓN 1' ? 'form-section' :''}}">
                                <h4>{{$formato->nombre}}</h4>
                                <div class="row">
                                    @foreach ($section['fields'] as $field)
                                        <div class="col-sm-{{$field['type'] == 'marcar' ? 12 : 6 }}">
                                            <div class="form-group">
                                                {!! Form::label($field['label'], $field['label'],  array('class' => $field['type'] == 'marcar' ? 'font-weight-bold' : '')) !!}
                                                @if($field['type'] == 'select')
                                                    {!! Form::{$field['type']}($field['label'], array_combine($field['options'], $field['options']), (isset($respuestas[preg_replace('~[ .]~', '_', $field['label'])]) ? $respuestas[preg_replace('~[ .]~', '_', $field['label'])] :null),  array('class' => 'form-control ff', 'placeholder' => 'Seleccionar...', 'required' => 'true')) !!}
                                                @elseif($field['type'] == 'marcar')
                                                    <div class="mx-4 mt-2">
                                                        @foreach ($field['values'] as $value)
                                                            <div style class="form-check form-check-inline border p-2 rounded mb-2" >
                                                                {!! Form::checkbox($field['label'].'[]', $value['value'], (isset($respuestas[preg_replace('~[ .]~', '_', $field['label'])]) ? (strpos($respuestas[preg_replace('~[ .]~', '_', $field['label'])], $value['value']) !== false):false), array('class' => 'form-check-input mr-2 ff', 'required' => 'true')) !!}
                                                                {!! Form::label($value['value'], $value['value'], array('class'=> 'form-check-label')) !!}  
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($field['type'] == 'checkbox')
                                                    <div>
                                                        @foreach ($field['values'] as $value)
                                                            <div style class="form-check form-check-inline" >
                                                                {!! Form::{$field['type']}($field['label'].'[]', $value, (isset($respuestas[preg_replace('~[ .]~', '_', $field['label'])]) ? (strpos($respuestas[preg_replace('~[ .]~', '_', $field['label'])], $value) !== false):false), array('class' => 'form-check-input mr-2 ff', 'required' => 'true')) !!}
                                                                {!! Form::label($value, $value, array('class'=> 'form-check-label')) !!}  
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($field['type'] == 'radio')
                                                    <div>
                                                        @foreach ($field['values'] as $value)
                                                            <div style class="form-check form-check-inline" >
                                                                {!! Form::{$field['type']}($field['label'], $value, (isset($respuestas[preg_replace('~[ .]~', '_', $field['label'])]) ? $respuestas[preg_replace('~[ .]~', '_', $field['label'])]:null), array('class' => 'form-check-input mr-2 ff', 'required' => 'true')) !!}
                                                                {!! Form::label($value, $value, array('class'=> 'form-check-label')) !!}  
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($field['type'] == 'cédula')
                                                {!! Form::text($field['label'], (isset($respuestas[preg_replace('~[ .]~', '_', $field['label'])]) ? $respuestas[preg_replace('~[ .]~', '_', $field['label'])]:null) ,  array('data-cedula'=>'true','class' => 'form-control ff', 'required' => 'true')) !!}
                                                @elseif($field['type'] == 'año')
                                                {!! Form::number($field['label'], (isset($respuestas[preg_replace('~[ .]~', '_', $field['label'])]) ? $respuestas[preg_replace('~[ .]~', '_', $field['label'])]:null) ,  array('data-año'=>'true','class' => 'form-control ff', 'min' => 1950, 'max' => 2070, 'required' => 'true')) !!}
                                                @else
                                                {!! Form::{$field['type'] == 'date' ? 'date' : $field['type']}($field['label'], (isset($respuestas[preg_replace('~[ .]~', '_', $field['label'])]) ? $respuestas[preg_replace('~[ .]~', '_', $field['label'])]:null) ,  array('required' => 'true','class' => 'form-control ff'.($field['type'] == 'date' ? ' datefield' : ''))) !!}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach 
                                </div>
                            </div>
                        @endforeach
                    
                        <button type="submit" class="btn btn-primary mt-3 float-right">
                            Actualizar Resolucion
                        </button>
                    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

