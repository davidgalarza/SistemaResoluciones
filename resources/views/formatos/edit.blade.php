@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">{{$formato->nombre}}</h3>
                    <span style="font-size: 1.1rem;" class="badge float-right badge-{{($formato->estado == "PUBLICO") ? "success": ($formato->estado == "ELIMINADO" ? "danger" : "secondary")}}">{{$formato->estado}}</span>
                </div>

                <div class="card-body">
                    @if(session()->get('success'))
                        <div class="alert alert-success">
                        {{ session()->get('success') }}  
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                    @endif
                    <div style="height: 1.6em"></div>
                    <div style="display: flow-root;">
                        <h3 class="float-left">Información del formato</h3>

                    </div>
                    <form method="POST" action="{{  url('/formatos/'.$formato->id) }}" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}
                        <!--Nombre-->
                        <label for="nombre" class="col-form-label text-md-right">Nombre del Formato</label>
                        <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') ?? $formato->nombre }}" required  autofocus>
                        @error('nombre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <!--Descripcion-->
                        <label for="descripcion" class="col-form-label text-md-right">Descripción</label>
                        <input id="descripcion" type="text" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" value="{{ old('descripcion') ?? $formato->descripcion }}" required >
                        @error('descripcion')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror    
                        
                        
                        <div class="row">

                            <div class="col-md-6">
                                <!--Estado-->
                                <label for="estado" class="col-form-label text-md-right">Estado</label>
                                <select id="estado" class="form-control @error('estado') is-invalid @enderror" name="estado" value="{{ old('estado') ?? $formato->estado }}" required >
                                    @if ($formato->estado == 'BORRADOR')
                                        <option {{ (old('estado') ?? $formato->estado) == "BORRADOR" ? 'selected' : '' }} value="BORRADOR">Borrador</option>
                                    @endif
                                    <option {{ (old('estado') ?? $formato->estado) == "PUBLICO" ? 'selected' : '' }} value="PUBLICO">Publico</option>
                                    <option {{ (old('estado') ?? $formato->estado) == "ELIMINADO" ? 'selected' : '' }} value="ELIMINADO">Eliminado</option>
                                </select>
                                @error('estado')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label style="opacity: 0" for="estado" class="col-form-label text-md-right">a</label>
                                <button type="submit" style="width: 100%" class="btn btn-primary full-width">
                                    Actualizar
                                   </button>
                            </div>

                        </div>
                    
                        
                    </form>

                    

                    @if (!isset($formato->form_schema) )
                    <div style="height: 1.6em"></div>
                    <div style="display: flow-root;">
                        <h3 class="float-left">Plantilla</h3>
                    </div>
                    <form id="form-template" method="POST" action="{{url('/formatos/procesar')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="text" hidden name="formulario_id" value="{{$formato->id}}">
                            <div class="col-md-10">
                                <label  class="col-form-label text-md-right">Plantilla (Word)</label>
                                <div class="custom-file">
                                    <input type="file" id="file-input" class="custom-file-input" value="{{old('template_file')}}" id="template_file" name="template_file" required>
                                    <label style="overflow: hidden" class="custom-file-label" id="label-input" for="template_file"> {{ session()->get('file_name') ?? 'Seleccinar archivo...'}}</label>
                                </div>
                                @error('template_file')
                                    <span class="invalid-feedback" style="display: block" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div  class="col-md-2">
                                <label style="opacity: 0" for="sub" class="col-form-label text-md-right">hideme</label>
                                <div>
                                    <button id="sub" type="submit" class="btn btn-primary my-auto">
                                        Procesar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    @endif

                    
                    @if (session()->get('form_schema') && session()->get('file_path'))
                        <form style="text-align: center" method="POST" class="my-3 mx-auto" action="{{  url('/formatos/'.$formato->id.'/plantilla') }}">
                            @csrf
                            {{ method_field('PUT') }}
                            <input type="text" name="form_schema" hidden value="{{json_encode(session()->get('form_schema'))}}">
                            <input type="text" name="ubicacion_plantilla" hidden value="{{session()->get('file_path')}}">
                            <button type="button" class="btn btn-dark" data-toggle="modal" data-target=".bd-example-modal-lg">
                                <i class="fas fa-eye"></i> Previsualizar
                            </button>
                            <button id="sub" type="submit" class= "btn mx-2 btn-success my-auto">
                                <i class="fas fa-plus"></i> Guardar plantilla
                            </button>
                        </form>
                    @endif
                  


                </div>
            </div>
        </div>
    </div>
</div>

@if (session()->get('form_schema') && !isset($formato->form_schema) )
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{$formato->nombre}} <span class="badge badge-secondary">Previsualización</span></h4>
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
                            <div class="col-md-{{$field['type'] == 'marcar' || $field['type'] == 'tabla' ? 12 : 6 }}">
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
                                    @elseif($field['type'] == 'tabla')
                                        <table class="table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    @foreach ($field['headers'] as $header)
                                                        <th>{{$header}}</th>
                                                    @endforeach
                                                </tr>

                                                <tbody>
                                                    
                                                </tbody>
                                            </thead>
                                      </table>
                                    @else
                                    {!! Form::{$field['type']}($field['label'], isset($field['default']) ? $field['default'] : null,  array('class' => 'form-control')) !!}
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
