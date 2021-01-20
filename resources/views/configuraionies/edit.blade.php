@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Editar Configuraciones</h3>                                
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
                    <form action="/configuraciones/actualizar" method="POST">
                        @method('POST')
                        @csrf
                        @foreach ($configuraciones as $configuracion)
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{$configuracion->key}}</label>
                                <input type="text" required value="{{$configuracion->value}}" class="form-control" name="{{$configuracion->key}}">
                            </div>
                        @endforeach
                        
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                      </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
