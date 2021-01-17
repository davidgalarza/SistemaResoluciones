@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Usuarios</h3>

                        <div class="float-right">
                            <a href="/usuarios/create">
                                <button type="button" class="btn btn-primary">
                                    <i class="fas fa-folder-plus"></i> Nuevo Usuario
                                </button>
                            </a>
                        </div>
                    
                </div>

                <div class="card-body">
                <div class="table-responsive">
                <table class="table table-hover">
                <thead>
                    <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Email</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Fecha de creación</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{$user->id}}</th>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            @foreach ($user->roles as $role)
                                {{$role->nombre}}
                            @endforeach 
                        </td>
                        <td>{{$user->created_at}}</td>
                        <td>
                            @if (($user->baneado)==0)
                            <span class="badge rounded-pill bg-success text-light">Activo</span>
                            @else
                            <span class="badge rounded-pill bg-danger text-light">Suspendido</span> 
                            @endif
                            </td>
                            <td>
                                <form action="{{route('usuarios.destroy',$user->id)}}" method="POST">
                                     <a href="{{route('usuarios.edit',$user->id)}}">
                                        <button type="button" class="btn btn-primary">Editar</button>
                                    </a>
                                    @if (($user->baneado)==0)
                                        @csrf
                                        @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Ban</button>
                                    @endif
                                   
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
</div>
@endsection
