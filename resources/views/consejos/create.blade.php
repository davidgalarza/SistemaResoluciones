@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Nuevo Consejo</h3>
                    
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

                    <form method="POST" action="{{ action('ConsejosController@store') }}" enctype="multipart/form-data">
                        @csrf

                        
                        <label for="fecha_consejo" class="col-form-label text-md-right">Fecha del consejo</label>
                        <input id="fecha_consejo" type="date" class="form-control @error('fecha_consejo') is-invalid @enderror" name="fecha_consejo" value="{{ old('fecha_consejo') }}" required  autofocus>
                        @error('fecha_consejo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        
                        <label for="presidente" class="col-form-label text-md-right">Presidente</label>
                        <input id="presidente" type="text" class="form-control @error('presidente') is-invalid @enderror" name="presidente" value="{{ old('presidente') }}" required >
                        @error('presidente')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror     
                        
                        <label for="tipo" class="text-left">Tipo de Sesion</label>
                        <select id="tipo" type="text" class="form-control @error('tipo') is-invalid @enderror" name="tipo" value="{{ old('tipo') }}" required autocomplete="tipo">
                            <option disabled selected>Seleccionar...</option>
                            
                            <option value="Ordinaria" {{ old('tipo') == 'Ordinaria'  ? 'selected' : ''}}>Ordinaria</option>
                            <option value="Extraordinaria" {{ old('tipo') == 'Extraordinaria'  ? 'selected' : ''}}>Extraordinaria</option>

                        </select>
                       
                        @error('tipo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror    

                    
                        <button type="submit" class="btn btn-primary mt-3 ">
                         Crear Consejo
                        </button>
                    </form>
                  
                    

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
