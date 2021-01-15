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

                    <table class="table">
                        <thead class="thead-dark">
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
                                <a href="{{ route('carreras.edit', $carrera->id)  }}"><button type="button" class="btn btn-info">Editar</button></a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>   
                                </form>                             
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
