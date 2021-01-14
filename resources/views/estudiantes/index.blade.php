@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Estudiantes</h3>
                    
                </div>

                <div class="card-body">
                    
                    <form action="{{ route('estudiantes.import.excel') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(Session::has('mensaje'))
                        <div class="alert alert-success" role="alert">
                        {{ Session::get('mensaje')}}
                        </div>
                        @endif
                        
                        <div class="mb-3">
                        <label class="form-label">Agrege el archivo Excel(.xlsx) de estudiantes</label>
                        <input style="padding: 0;height: auto;" class="form-control" type="file"  name="file" >
                        @error('file')
                           <span class="invalid-feedback" style="display: block" role="alert">
                               <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                        </div>
                    <button class="btn btn-primary">Actualizar Listado</button>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
