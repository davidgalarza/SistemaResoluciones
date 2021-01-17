@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Carreras</h3>

                    <div class="float-right">
                        <a href="/carreras/nuevo">
                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-folder-plus"></i> Agregar Carrera
                            </button>
                        </a>
                    </div>

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
                    <div class="py-3">
                        <form action="">
                            <div class="input-group my-2">       
                                <input type="search" placeholder="Criterio de búsqueda" value="" name="search" class="form-control" aria-label="Criterio de búsqueda">

                                <button class="btn btn-secondary ml-3" type="submit" >Buscar</button>
                            </div>

                        </form>    
                        
                        @if($search)
                        <h6>
                            <div class="alert alert-secondary" role="alert">
                                El resultado de la búsqueda "{{ $search }}" es:
                            </div>
                        </h6>
                        @endif
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">CARRERA</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carreras as $carrera)
                            <tr>
                                <th scope="row">{{$carrera->id}}</th>
                                <td>{{$carrera->nombre}}</td>
                                <td>
                                    <form action="{{ route('carreras.destroy', $carrera->id) }}" method="POST">
                                        {{-- <a href="{{ route('carreras.edit', $carrera->id)  }}"><button type="button"
                                            class="btn btn-info">Editar</button></a> --}}
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger boton_eliminar">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $carreras->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_eliminar" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel"><strong>¿Seguro de eliminar?</strong></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Eliminada la carrera se perderan todos los datos y procesos antiguos.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" id="delete" class="btn btn-danger">Eliminar</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('head')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>

<script src="{{ asset('js/editar_carreras.js')}}"></script>
@endpush