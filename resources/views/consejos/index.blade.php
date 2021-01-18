@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Consejos</h3>
                    @can('ABOGADO')
                        <div class="float-right">
                            <a href="/consejos/nuevo">
                                <button id="boton_nuevo" data-puede="{{$puedeCrearConsejo ? 'true' : 'false' }}" type="button" class="btn btn-primary">
                                    <i class="fas fa-folder-plus"></i> Nueva Consejo
                                </button>
                            </a>
                        </div>
                    @endcan
                    
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
                    @if(session()->get('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    @foreach ($consejos as $consejo)
                        <div class="border-bottom mt-3 pb-3">
                            <div class="row">

                                <div style="flex: 1" class="col-auto">
                                    <div>
                                        <h5 class="font-weight-bold">Consejo: {{(new Carbon\Carbon($consejo->fecha_consejo))->formatLocalized('%A %d de %B del %Y')}}</h5>
                                    </div>
                                    <p class=""><span class="font-weight-bold">Presidente: </span>{{$consejo->presidente}}</p>
                                </div>

                                @if ($consejo->estado == "ENPROCESO")
                                    <div  style="flex: 0" class="col align-self-center">
                                        <a href="/consejos/{{$consejo->id}}/editar" class="btn btn-warning font-weight-bold font-size-5"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square d-inline" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg></a>
                                    </div>
                                @else
                                    <div  style="flex: 0" class="col align-self-center">
                                        <a href="/consejos/{{$consejo->id}}/editar" class="btn btn-dark font-weight-bold font-size-5"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                          </svg></a>
                                    </div>
                                @endif

                            </div>
                            
                            <span class="badge badge-{{($consejo->estado == "ENPROCESO") ? "primary": ($consejo->estado == "CANCELADO" ? "danger" : "success")}}">{{$consejo->estado}}</span>
                        </div>
                    @endforeach

                    @if (count($consejos) == 0)
                        <div class="my-4" style="text-align: center">
                            <i style="opacity: 0.7;font-size: 4em" class="fas fa-folder-open mb-2"></i>
                            <p style="opacity: 0.7">Sin resultados</p>
                        </div>
                    @endif
                    <div class="text-center mt-3">
                        <div  class="d-inline-block">
                            {{$consejos->appends(request()->except('page'))->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_en_proceso" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><strong>No se puede iniciar el consejo</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Ya existe un consejo <strong>"En Proceso"</strong> para crear oto finalizelo o cancelelo.
            </div>
            <div class="modal-footer">
               
                <button type="button" data-dismiss="modal" id="cancelar" class="btn btn-info">Entendido</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('head')

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>

<!-- Scripts -->
<script src="{{ asset('js/consejos.js')}}"></script>
@endpush