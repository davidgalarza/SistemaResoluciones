@extends('layouts.app')

@section('content')
<input id="id_consejo" value="{{$consejo->id}}" type="text" hidden>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Consejo {{$consejo->fecha_consejo}}</h3>                    
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

                    <form method="POST" action="{{  url('/consejos/'.$consejo->id) }}" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}

                        <div class="row">
                            <div class="col-md-10">
                                <label for="presidente" class="col-form-label text-md-right">Presidente</label>
                                <input id="presidente" type="text" class="form-control @error('presidente') is-invalid @enderror" name="presidente" value="{{ old('presidente') ?? $consejo->presidente}}" required >
                                @error('presidente')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror    
                            </div>
                            <div class="col-md-2">
                                <label style="opacity: 0" for="presidente" class="col-form-label text-md-right">P</label>
                                <button type="submit" class="btn btn-primary  ">
                                    Actualizar
                                   </button>
                            </div>
                        </div>


                    </form>

                    <div class="mt-5">
                        <h4 style="display: inline-block" class="floar-left">Resoluciones</h4>
                        <button type="button" class="btn btn-dark float-right" data-toggle="modal" data-target=".bd-example-modal-lg">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    </div>
                    
                    @foreach ($resoluciones as $resolucion)
                        <div class="row">
                            {{$resolucion->id}}
                            <a href="/resoluciones/{{$resolucion->id}}/descargar" class="btn btn-warming">DESCARGAR</a>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg {{session()->get('estudiante') !== null ? 'show' : ''}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Nueva Resolucion</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <div class="modal-body">

                
                <div class="row">
                    <div class="col-md-10">
                        <label for="cedula" class="col-form-label text-md-right">Cedula del estudiante</label>
                        <input id="cedula" type="text" class="form-control @error('cedula') is-invalid @enderror" name="cedula" value="{{ old('cedula') }}" required >
                        @error('presidente')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror    
                    </div>
                    <div class="col-md-2">
                        <label style="opacity: 0;" for="presidente" class="col-form-label text-md-right">P</label>
                        <button id="boton_buscar_estudiante" style="display: block" class="btn btn-primary  ">
                            <i class="fas fa-search"></i>  Buscar
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label for="tipo_resolucion" class="col-form-label text-md-right">Tipo de resolucion</label>
                        <select disabled id="tipo_resolucion" type="text" class="form-control " name="tipo_resolucion"  required autocomplete="tipo_resolucion">
                            <option>Seleccionar...</option>
                            @foreach ($formatos as $formato)
                                <option data-carrera="{{$formato->carrera_id}}" value="{{$formato->id}}">{{$formato->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="contenedor_formulario">

                </div>


        </div>
    </div>
    </div>
</div>

@endsection

@push('head')
<style>

    iframe {
        border: none !important;
        overflow: hidden;
        width: 100%;
    height: auto;
    overflow: scroll;
    -webkit-overflow-scrolling: touch;
    }

    #contenedor_formulario{
        
    }
</style>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
<!-- Scripts -->
<script src="{{ asset('js/editar_consejos.js')}}"></script>
@endpush