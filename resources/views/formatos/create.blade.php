@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Añadir Formato de resolución</h3>
                    
                </div>

                <div class="card-body">
                    
                    <form method="POST" action="{{ action('FormatosController@store') }}" enctype="multipart/form-data">
                        @csrf

                        <!--Nombre-->
                        <label for="nombre" class="col-form-label text-md-right">Nombre del Formato</label>
                        <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required  autofocus>
                        @error('nombre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <!--Descripcion-->
                        <label for="descripcion" class="col-form-label text-md-right">Descripción</label>
                        <input id="descripcion" type="text" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" value="{{ old('descripcion') }}" required >
                        @error('descripcion')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror     
                        

                        <label for="carrera_id" class="text-left">Carrera del formato</label>
                        <select id="carrera_id" type="text" class="form-control @error('carrera_id') is-invalid @enderror" name="carrera_id" value="{{ old('carrera_id') }}" required autocomplete="carrera_id">
                            <option>Seleccionar...</option>
                            @foreach ($carreras as $carrera)
                                <option value="{{$carrera->id}}" {{ old('carrera_id') == $carrera->id  ? 'selected' : ''}}>{{$carrera->nombre}}</option>
                            @endforeach

                        </select>

                        @error('carrera_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        

                    
                        <button type="submit" class="btn btn-primary mt-3 ">
                         Crear
                        </button>
                    </form>
                  
                </div>
            </div>
        </div>
    </div>

    
</div>

@if (session()->get('form_schema'))
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Previsualización<span class="badge badge-secondary">Previsualización</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <div class="modal-body">
                @foreach (session()->get('form_schema') as $section)
                    <h4>{{$section['title']}}</h4>
                    @if (array_key_exists('description', $section))
                        @if ($section['description'] != "")
                            <p class="alert alert-light" role="alert">{{$section['description']}}</p>
                        @endif  
                    @endif

                    
                    <div class="row">
                        @foreach ($section['fields'] as $field)
                            <div class="col-md-{{$field['type'] == 'marcar' ? 12 : 6 }}">
                                <div class="form-group">
                                    {!! Form::label($field['label'], $field['label'], array('class' => $field['type'] == 'marcar' ? 'font-weight-bold' : '')) !!}
                                    @if($field['type'] == 'select')
                                        {!! Form::{$field['type']}($field['label'], $field['options'], null,  array('class' => 'form-control', 'placeholder' => 'Seleccionar')) !!}


                                    @elseif($field['type'] == 'radio' || $field['type'] == 'checkbox')
                                        <div>
                                            @foreach ($field['values'] as $value)
                                                <div style class="form-check form-check-inline" >
                                                    {!! Form::{$field['type']}($field['label'], $value, null, array('class' => 'form-check-input mr-2')) !!}
                                                    {!! Form::label($value, $value, array('class'=> 'form-check-label')) !!}  
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($field['type'] == 'cédula' || $field['type'] == 'ruc')
                                    {!! Form::text($field['label'], (isset($defaultValues[preg_replace('~[ .]~', '_', $field['label'])]) ? $defaultValues[preg_replace('~[ .]~', '_', $field['label'])]:null) ,  array('data-cedula'=>'true','class' => 'form-control ff')) !!}
                                    @elseif($field['type'] == 'anio')
                                    {!! Form::text($field['label'], (isset($defaultValues[preg_replace('~[ .]~', '_', $field['label'])]) ? $defaultValues[preg_replace('~[ .]~', '_', $field['label'])]:null) ,  array('data-año'=>'true','class' => 'form-control ff')) !!}
                                    @else
                                    {!! Form::{$field['type']}($field['label'], null,  array('class' => 'form-control')) !!}
                                    @endif
                                </div>
                            </div>
                        @endforeach 
                    </div>
                @endforeach
            </div>
        </div>
        </div>
    </div>
@endif
@endsection
