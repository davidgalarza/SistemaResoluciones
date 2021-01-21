@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Formatos Resoluciones</h3>
                    
                    <div class="float-right">
                        <a href="/formatos/nuevo">
                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-folder-plus"></i> Nuevo Formato
                            </button>
                        </a>
                    </div>
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

                    <div class="py-3">
                        <strong>Buscar Formato</strong>
                        <form action="">
                            <input type="text" hidden name="estado" value="{{$tipo ?? ''}}">
                            <div class="input-group my-2">
                                <div class="input-group-prepend">
                                  <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      {{$estado == '' ? 'Todos' : ($estado) }}
                                  </button>
                                  <div class="dropdown-menu">
                                    <a class="dropdown-item" href="?q={{$q ?? ''}}&estado=PUBLICO">Publico</a>
                                    <a class="dropdown-item" href="?q={{$q ?? ''}}&estado=ELIMINADO">Eliminado</a>
                                    <a class="dropdown-item" href="?q={{$q ?? ''}}&estado=BORRADOR">Borrador</a>

                                    

                                    <div role="separator" class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="?q={{$q ?? ''}}&tipo=">Todos</a>
                                  </div>
                                </div>
                                <input type="search" placeholder="Criterio de búsqueda" value="{{$q ?? ''}}" name="q" class="form-control" aria-label="Criterio de búsqueda">

                                <button class="btn btn-secondary ml-3" type="submit" >Buscar</button>
                            </div>

                        </form>    
                    </div>

                    @foreach ($formatos as $formato)
                        <div class="border-bottom mt-3 pb-3">
                            <div class="row">

                                <div style="flex: 1" class="col-auto">
                                    <div>
                                        <h5 class="font-weight-bold">{{$formato->nombre}}</h5>
                                    </div>
                                    <p class="">{{$formato->descripcion}}</p>
                                    <p class=""><span class="font-weight-bold">Última actualización: </span>{{$formato->updated_at}}</p>
                                </div>

                                <div  style="flex: 0" class="col align-self-center">
                                    <a href="/formatos/{{$formato->id}}/editar" class="btn btn-warning font-weight-bold font-size-5"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square d-inline" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                      </svg></a>
                                </div>

                            </div>
                            <?
                                setlocale(LC_CTYPE, 'es_ES.UTF8');
                            ?>
                            <span class="badge badge-dark">{{$carreras[array_search($formato->carrera_id, array_column($carreras, 'id'))]['nombre']}}</span>
                            <span class="badge badge-{{($formato->estado == "PUBLICO") ? "success": ($formato->estado == "ELIMINADO" ? "danger" : "secondary")}}">{{$formato->estado}}</span>
                        </div>
                    @endforeach
                    @if (count($formatos) == 0)
                                    <div class="my-4" style="text-align: center">
                                        <i style="opacity: 0.7;font-size: 4em" class="fas fa-folder-open mb-2"></i>
                                        <p style="opacity: 0.7">Sin resultados</p>
                                    </div>
                    @endif
                        <div class="text-center mt-3">
                            <div  class="d-inline-block">
                                {{$formatos->appends(request()->except('page'))->links()}}
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
