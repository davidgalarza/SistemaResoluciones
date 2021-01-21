@extends('layouts.app')

@section('content')
<input id="id_consejo" value="{{$consejo->id}}" type="text" hidden>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Consejo del
                        {{(new \Carbon\Carbon($consejo->fecha_consejo))->formatLocalized('%d de %B del %Y')}}</h3>
                    <span style="font-size: 1.2rem"
                        class="badge float-right badge-{{($consejo->estado == "ENPROCESO") ? "primary": ($consejo->estado == "CANCELADO" ? "danger" : "success")}}">{{$consejo->estado}}</span>
                </div>

                <div class="card-body">
                    @if(session()->get('success'))
                    <div class="alert alert-success">
                        <strong>{{ session()->get('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    @if(session()->get('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <form method="POST" action="{{  url('/consejos/'.$consejo->id) }}" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}

                        <div class="row">

                            @can('ABOGADO')
                            @if ($consejo->estado == 'ENPROCESO')
                            <div class="col-md-12">
                                <label for="presidente" class="col-form-label text-md-right">Presidente</label>
                                <input id="presidente" type="text"
                                    class="form-control @error('presidente') is-invalid @enderror" name="presidente"
                                    value="{{ old('presidente') ?? $consejo->presidente}}" required>
                                @error('presidente')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="estado" class="col-form-label text-md-right">Estado del consejo</label>
                                <select id="estado" class="form-control @error('estado') is-invalid @enderror"
                                    name="estado" value="{{ old('estado') ?? $consejo->estado }}" required>
                                    @if ($consejo->estado == 'ENPROCESO')
                                    <option {{ (old('estado') ?? $consejo->estado) == "ENPROCESO" ? 'selected' : '' }}
                                        value="ENPROCESO">En Proceso</option>
                                    @endif
                                    <option {{ (old('estado') ?? $consejo->estado) == "FINALIZADO" ? 'selected' : '' }}
                                        value="FINALIZADO">Finalizado</option>
                                    <option {{ (old('estado') ?? $consejo->estado) == "CANCELADO" ? 'selected' : '' }}
                                        value="CANCELADO">Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo" class=" col-form-label text-md-right">Tipo de Sesión</label>
                                <select id="tipo" type="text" class="form-control  @error('tipo') is-invalid @enderror"
                                    name="tipo" value="{{ old('tipo') }}" required autocomplete="tipo">
                                    <option disabled selected>Seleccionar...</option>

                                    <option value="Ordinaria"
                                        {{ old('tipo') == 'Ordinaria' || $consejo->tipo == 'Ordinaria' ? 'selected' : ''}}>
                                        Ordinaria</option>
                                    <option value="Extraordinaria"
                                        {{ old('tipo') == 'Extraordinaria' || $consejo->tipo == 'Extraordinaria' ? 'selected' : ''}}>
                                        Extraordinaria</option>

                                </select>

                                @error('tipo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 text-end" style="text-align: end;">
                                <label style="opacity: 0" for="presidente"
                                    class="col-form-label d-block text-md-right">P</label>
                                <button type="submit" id="boton_actulizar" class="btn btn-primary f-end ">
                                    Actualizar
                                </button>
                            </div>
                            @else

                            <div class="col-12">
                                <p style="font-size: 1rem"><span class="font-weight-bold">Presidente: </span>
                                    {{$consejo->presidente}}</p>
                            </div>
                            <div class="col-12">
                                <p style="font-size: 1rem"><span
                                        class="font-weight-bold">{{$consejo->estado == "CANCELADO" ? 'Cancelado' : 'Finalizado'}}
                                        el: </span>
                                    {{(new \Carbon\Carbon($consejo->updated_at))->formatLocalized('%A %d de %B del %Y')}}
                                </p>
                            </div>

                            <div class="col-12">
                                <a class="btn btn-warning" href="/consejos/{{$consejo->id}}/acta">
                                    Descargar Acta
                                </a>
                            </div>
                            @endif

                            @endcan

                        </div>


                    </form>

                    <div class="mt-5">
                        <h4 style="display: inline-block" class="floar-left">Resoluciones</h4>
                        @if ($consejo->estado == 'ENPROCESO')
                        <button type="button" class="btn btn-dark float-right" data-toggle="modal"
                            data-target=".bd-example-modal-lg">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                        @endif

                    </div>

                    <div class="py-3">
                        <strong>Buscar Formato</strong>
                        <form action="">
                            <div class="input-group my-2">

                                <input type="search" placeholder="Criterio de búsqueda" value="{{$q ?? ''}}" name="q"
                                    class="form-control" aria-label="Criterio de búsqueda">


                            </div>

                        </form>
                    </div>


                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">#Resolución</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Estudiante</th>
                                <th style="width: 15%" scope="col">Carrera</th>
                                <th scope="col">Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resoluciones as $resolucion)
                            <tr>
                                @php
                                $estudiante = \App\Estudiante::findOrFail($resolucion->estudiante_id);
                                @endphp
                                <th class="text-center" scope="row">{{$resolucion->nummero_resolucion}}</th>
                                <td>{{\App\Formato::findOrFail($resolucion->formato_id)->nombre}}</td>

                                <td>{{ $estudiante->nombres}}<br />{{ $estudiante->apellidos}}</td>
                                <td>{{\App\Carrera::findOrFail($estudiante->carrera_id)->nombre}}</td>
                                <td><a href="/resoluciones/{{$resolucion->id}}/descargar" class="btn btn-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                            <path
                                                d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                            <path
                                                d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                        </svg>
                                    </a>

                                    @if ($consejo->estado == 'ENPROCESO')
                                    <a href="/resoluciones/{{$resolucion->id}}/editar" class="btn btn-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                        </svg>
                                    </a>

                                    <form style="display: inline-block" method="POST"
                                        action="/resoluciones/{{$resolucion->id}}/eliminar">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger boton_eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                <path fill-rule="evenodd"
                                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                            </svg>
                                        </button>
                                    </form>

                                    @endif
                                </td>
                            </tr>
                            @endforeach


                        </tbody>
                    </table>

                    @if (count($resoluciones) == 0)
                    <div class="my-4" style="text-align: center">
                        <i style="opacity: 0.7;font-size: 4em" class="fas fa-folder-open mb-2"></i>
                        <p style="opacity: 0.7">Sin resultados</p>
                    </div>
                    @endif
                    <div class="text-center mt-3">
                        <div class="d-inline-block">
                            {{$resoluciones->appends(request()->except('page'))->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_eliminar" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><strong>¿Seguro de eliminar?</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Eliminada la resolucion se perderan todos los datos ingresados durante su creación.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="delete" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_finalizar" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><strong>¿Seguro de Finalizar el Consejo?</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Finalizado el consejo no podra modificar ni eliminar las resoluciones creadas en este.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="finalizar" class="btn btn-success">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_cancelado" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><strong>¿Seguro de Cancelar el Consejo?</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Finalizado el consejo no podra modificar ni eliminar las resoluciones creadas en este.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="cancelar" class="btn btn-danger">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_resolucion"
    class="modal fade bd-example-modal-lg {{session()->get('estudiante') !== null ? 'show' : ''}}" tabindex="-1"
    role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nueva Resolución</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div id="contenedor_mensaje_modal">
                    <div class="alert alert-success" role="alert">
                        <strong>Resolución creada exitosamente</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-10">
                        <label for="cedula" class="col-form-label font-weight-bold text-md-right">Cédula del
                            estudiante</label>
                        <input id="cedula" type="text" class="form-control @error('cedula') is-invalid @enderror"
                            name="cedula" value="{{ old('cedula') }}" required>

                        <span id="error_cedula" class="invalid-feedback" role="alert">
                            <strong>No se encontró al estudiante</strong>
                        </span>

                    </div>
                    <div class="col-md-2">
                        <label style="opacity: 0;" for="presidente" class="col-form-label text-md-right">P</label>
                        <span id="contenedor_accion_es">
                            <button id="boton_buscar_estudiante" onclick="buscarEstudiante()" style="display: block"
                                class="btn btn-primary  ">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </span>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div id="contenedor_info_es" style="border-style: dashed !important;
                        border-width: 2px !important;" class="p-3 border rounded my-3 text-center">
                            <img src="/images/student.svg" style="max-width: 3rem" alt="" class="d-inline-block "
                                srcset="">
                            <p class="font-weight-bold d-inline-block">Seleccione un estudiante</p>
                        </div>


                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label for="tipo_resolucion" class="col-form-label font-weight-bold text-md-right">Tipo de
                            resolución</label>
                        <select id="tipo_resolucion" type="text" class="form-control selectpicker"
                            data-live-search="true" name="tipo_resolucion" data-live-search="true"
                            autocomplete="tipo_resolucion">
                            <option value="" selected disabled>Seleccionar...</option>
                            @foreach ($formatos as $formato)
                            <option class="font-weight-bold" data-carrera="{{$formato->carrera_id}}"
                                value="{{$formato->id}}">{{$formato->nombre}}</option>
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

    .btn {
        font-weight: bolder;
    }

    #contenedor_formulario {}

    .bootstrap-select {
        border: 1px solid #ced4da !important
    }
</style>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" defer
    href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<!-- Scripts -->
<script src="{{ asset('js/editar_consejos.js')}}"></script>
@endpush