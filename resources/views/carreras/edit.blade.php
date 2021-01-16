@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Editar Carrera: {{ $carrera->nombre }}</h3>                                
                </div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                    <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                    </div>
                    @endif
                    <form action="{{ route('carreras.update', $carrera->id) }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="form-group">
                          <label for="exampleInputEmail1">Nombre de la Carrera</label>
                          <input type="text" class="form-control" name="nombre" value="{{ $carrera->nombre }}" placeholder="Escribe el nombre de la nueva carrera">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a href="/carreras">                            
                        <button type="button" class="btn btn-danger">Cancelar</button>
                        </a>
                      </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
