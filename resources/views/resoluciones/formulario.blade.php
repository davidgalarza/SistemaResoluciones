@extends('layouts.bootstrap')

@section('content')
<form id="template_form" method="POST" action="/resoluciones/anadir" enctype="multipart/form-data">
    @csrf
    <input type="text" name="id_consejo" value="{{$consejo->id}}" hidden>
    <input type="text" name="id_formato" value="{{$formato->id}}" hidden>
    <input type="text" name="id_estudiante" value="{{$estudiante->id}}" hidden>
    <input type="text" name="Nombres Apellidos Estudiante" value="{{$estudiante->nombres}} {{$estudiante->apellidos}}"
        hidden>
    <input type="text" name="Cédula Estudiante" value="{{$estudiante->cedula}}" hidden>
    <input type="text" name="Correo Personal Estudiante" value="{{$estudiante->correo}}" hidden>
    <input type="text" name="Correo UTA Estudiante" value="{{$estudiante->correoUTA}}" hidden>
    <input type="text" name="Carrera Estudiante" value="{{$carrera->nombre}}" hidden>
    <input type="text" name="Telefono Estudiante" value="{{$estudiante->telefono}}" hidden>
    <input type="text" name="Matricula Estudiante" value="{{$estudiante->matricula}}" hidden>
    <input type="text" name="Folio Estudiante" value="{{$estudiante->folio}}" hidden>
    <input type="text" name="Periodo Académico" value="{{$periodo}}" hidden>

    @foreach ($formSchema as $section)
    <div id="{{$section['title']}}" class="{{$section['title']!='SECCIÓN 1' ? 'form-section' :''}}">
        <div style="display: none" id="errorEditarTabla" class="alert alert-danger" role="alert">
           <strong>Primero terminar de editar la tabla</strong>
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <h4>{{$formato->nombre}}</h4>
        <div class="row">
            @foreach ($section['fields'] as $field)
            <div class="col-sm-{{$field['type'] == 'marcar' || $field['type'] == 'tabla' ? 12 : 6 }}">
                <div class="form-group">
                    {!! Form::label($field['label'], $field['label'], array('class' => $field['type'] == 'marcar' ?
                    'font-weight-bold' : '')) !!}
                    @if($field['type'] == 'select')
                    {!! Form::{$field['type']}($field['label'], array_combine($field['options'], $field['options']),
                    (isset($defaultValues[preg_replace('~[ .]~', '_', $field['label'])]) ?
                    $defaultValues[preg_replace('~[ .]~', '_', $field['label'])] :null), array('class' => 'form-control
                    ff', 'placeholder' => 'Seleccionar...', 'required' => 'true')) !!}
                    @elseif($field['type'] == 'marcar')
                    <div class="mx-4 mt-2">
                        @foreach ($field['values'] as $value)
                        <div style class="form-check form-check-inline border p-2 rounded mb-2">
                            {!! Form::checkbox($field['label'].'[]', $value['value'],
                            (isset($defaultValues[preg_replace('~[ .]~', '_', $field['label'])]) ?
                            (strpos($defaultValues[preg_replace('~[ .]~', '_', $field['label'])], $value['value']) !==
                            false):false), array('class' => 'form-check-input mr-2 ff', 'required' => 'true')) !!}
                            {!! Form::label($value['value'], $value['value'], array('class'=> 'form-check-label')) !!}
                        </div>
                        @endforeach
                    </div>
                    @elseif($field['type'] == 'checkbox')
                    <div>
                        @foreach ($field['values'] as $value)
                        <div style class="form-check form-check-inline">
                            {!! Form::{$field['type']}($field['label'].'[]', $value,
                            (isset($defaultValues[preg_replace('~[ .]~', '_', $field['label'])]) ?
                            (strpos($defaultValues[preg_replace('~[ .]~', '_', $field['label'])], $value) !==
                            false):false), array('class' => 'form-check-input mr-2 ff', 'required' => 'true')) !!}
                            {!! Form::label($value, $value, array('class'=> 'form-check-label')) !!}
                        </div>
                        @endforeach
                    </div>
                    @elseif($field['type'] == 'radio')
                    <div>
                        @foreach ($field['values'] as $value)
                        <div style class="form-check form-check-inline">
                            {!! Form::{$field['type']}($field['label'], $value, (isset($defaultValues[preg_replace('~[
                            .]~', '_', $field['label'])]) ? $defaultValues[preg_replace('~[ .]~', '_',
                            $field['label'])]:null), array('class' => 'form-check-input mr-2 ff', 'required' => 'true'))
                            !!}
                            {!! Form::label($value, $value, array('class'=> 'form-check-label')) !!}
                        </div>
                        @endforeach
                    </div>
                    @elseif($field['type'] == 'cédula')
                    {!! Form::text($field['label'], (isset($defaultValues[preg_replace('~[ .]~', '_', $field['label'])])
                    ? $defaultValues[preg_replace('~[ .]~', '_', $field['label'])]:null) ,
                    array('data-cedula'=>'true','class' => 'form-control ff', 'required' => 'true')) !!}
                    @elseif($field['type'] == 'año')
                    {!! Form::number($field['label'], (isset($defaultValues[preg_replace('~[ .]~', '_',
                    $field['label'])]) ? $defaultValues[preg_replace('~[ .]~', '_', $field['label'])]:null) ,
                    array('data-año'=>'true','class' => 'form-control ff', 'min' => 1950, 'max' => 2070, 'required' =>
                    'true')) !!}
                    @elseif($field['type'] == 'tabla')
                    <div class="tablaContenedor">
                        <input type="text" class="inputTabla" name="{{$field['label']}}" value="" hidden>
                        <div style="text-align: end">
                            <p id="new-row-button" class="btn btn-dark float-right d-inline">
                                Anadir Fila
                            </p>
                        </div>
                        <table style="font-size: 8pt" class="table table-striped table-bordered" id="table">
                            <thead class="thead-dark">
                                <tr>
                                    @foreach ($field['headers'] as $header)
                                    <th scope="col">{{$header}}</th>
                                    @endforeach
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    @foreach ($field['headers'] as $header)
                                    <td> </td>
                                    @endforeach
                                </tr>


                            </tbody>
                        </table>
                    </div>

                    @else

                    {!! Form::{$field['type'] == 'date' ? 'text' : $field['type']}($field['label'],
                    isset($field['default']) ?$field['default'] : null , array('required' => 'true','class' =>
                    'form-control ff'.($field['type'] == 'date' ? ' datefield' : ''))) !!}
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach


    <button type="submit" id="boton_enviar" class="btn btn-primary mt-3 float-right">
        Crear Resolucion
    </button>

</form>
@endsection

@push('head')

<style>
    body {
        background-color: white !important;
    }
</style>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="{{ asset('js/new_resolucion.js')}}"></script>
<script src="{{ asset('js/bstable.js')}}"></script>

<script>
    $(document).ready(() => {
            console.log($('#new-row-button').html());
                var editableTable = new BSTable("table", {
                    $addButton: $('#new-row-button'),
                    onEdit: function() {
                        $('#errorEditarTabla').hide();
                    }, 
                    onBeforeDelete: function() {}, 
                    onDelete: function() {}, 
                    onAdd: function() {
                        console.log('HOLA');
                    },
                });
                editableTable.init();
                
        });


            
        
        


</script>

@endpush