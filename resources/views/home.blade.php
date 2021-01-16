@extends('layouts.app')

<!-- VISTA ESTADISTICAS -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src=".\js\grafica.js"></script> 
<link href=".\css\cargar.css" rel="stylesheet" type="text/css">

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">

                    @can('ADMINISTRADOR')
                        <h1 style="text-align:center;">ESTADISTICAS RESOLUCIONES</h1>
                        <div class="row">
                        @if ((count($carreras)>0) && (count($formatoInicial)>0))
                        <div class="col-md-6">
                                <label for="" class="control-label">Seleccione una carrera</label>
                                <select name="Carreras" id="Carreras" class="form-control">
                                    @foreach($carreras as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                    </select>
                              
                            </div>
                            <div class="col-md-6">
                                <label for="" class="control-label">Seleccione un formato</label>
                                    <select name="Formatos" id="Formatos" class="form-control">
                                    @foreach($formatoInicial as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                    </select>
                            </div>
                        </div>
                        
                        <div style="position: relative; position: absolute;top:50%; left: 50%;margin-top: -100px; margin-left: -100px;">
                            <div id="cargador" style="position: absolute; " class="loader" >Loading...</div>
                        </div>

                        <div id="top_x_div" style="width: auto; height: 600px; position: relative; margin-top: 70px;"></div>

                        <div id="smsAlerta1" style="margin-top: 10px;"> 
                            <div class="alert alert-danger" style="text-align: center;">No existen datos para graficar</div>

                        </div>
                       
                        @else    
                        <div id="smsAlerta2" style="margin-top: 10px; width: 100%;"> 
                            <div class="alert alert-danger" style="text-align: center;">No existen carreras o formatos disponibles</div>
                        </div>
                        @endif
   
                    @endcan

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
