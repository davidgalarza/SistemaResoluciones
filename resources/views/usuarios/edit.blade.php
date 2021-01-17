@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Editar usuario: {{$user->name}}</h2>

               
                    
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
                    <form action="{{route('usuarios.update', $user->id)}}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="mb-3">
                          <label for="name">Nombre</label>
                          <input type="text" class="form-control" name="name" value="{{$user->name}}">
                        </div>
                        <div class="mb-3">
                          <label for="email">Email</label>
                          <input type="email" class="form-control" name="email" value="{{$user->email}}">
                        </div>
                        <div class="mb-3">
                            <label for="rol">Rol</label>
                            <select name="rol" class="form-control">
                                <option selected disabled>Elija un rol</option>
                                @foreach ($roles as $rol)
                                    @if ($rol->nombre==str_replace(array('["','"]'),'',$user->roles->flatten()->pluck('nombre')->unique()))
                                    <option value="{{$rol->id}}" selected>{{$rol->nombre}}</option>
                                    @else
                                    <option value="{{$rol->id}}">{{$rol->nombre}}</option>
                                    @endif
                                   
                              
                                    
                                @endforeach
                            </select>
                          </div>
                          <div class="mb-3">
                          
                          <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="chEstado" name="estado" @if (($user->baneado)==0) checked @endif>
                            <label class="custom-control-label" for="chEstado">Estado</label>
                          </div>
                          
                          
                          
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        <a href="/usuarios">
                        <button type="button" class="btn btn-danger">Cancelar</button>
                        </a>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
