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
                        <h1 style="text-align:center;">ESTADÍSTICAS RESOLUCIONES</h1>
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
                        
                        <div style="padding-top: 50px;">
                        <div style="display: flex; justify-content: center; ">
                            <div id="cargador" style="position: absolute; " class="loader" >Cargando...</div>
                        </div>

                        <div id="chart_wrap">
                            <div id="top_x_div"></div>
                        </div>
                       

                        <div id="smsAlerta1" style="margin-top: 10px;"> 
                            <div class="alert alert-danger" style="text-align: center;">No existen datos para graficar</div>

                        </div>

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
