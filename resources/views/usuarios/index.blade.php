@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Usuarios</h3>

                        <div class="float-right">
                            <a href="/usuarios/nuevo">
                                <button type="button" class="btn btn-primary">
                                    <i class="fas fa-folder-plus"></i> Nuevo Usuario
                                </button>
                            </a>
                        </div>
                    
                </div>

                <div class="card-body">

                    

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
