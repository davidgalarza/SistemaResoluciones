@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Nuevo Usuario</h3>

               
                    
                </div>

                <div class="card-body">
                    <div class="container">
                       @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    <form action="/usuarios" method="POST">
                        @csrf
                        <div class="mb-3">
                          <label for="name">Nombre</label>
                          <input type="text" class="form-control" name="name" placeholder="Escriba el nombre">
                        </div>
                        <div class="mb-3">
                          <label for="email">Email</label>
                          <input type="email" class="form-control" name="email" placeholder="Escriba el email">
                        </div>
                        <div class="mb-3">
                            <label for="rol">Rol</label>
                            <select name="rol" class="form-control">
                                <option value="" selected disabled>Elija un rol</option>
                                @foreach ($roles as $rol)
                                    <option value="{{$rol->id}}">{{$rol->nombre}}</option>
                                @endforeach
                            </select>
                          </div>
                        <div class="mb-3">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" name="password" placeholder="Escriba la contraseña">
                          </div>
                        <button type="submit" class="btn btn-primary">Registrar</button>
                        <button type="reset" class="btn btn-danger">Cancelar</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
