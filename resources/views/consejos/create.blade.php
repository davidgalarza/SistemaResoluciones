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
                    @if(session()->get('error'))
                        <div class="alert alert-danger">
                            <strong>{{ session()->get('error') }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ action('ConsejosController@store') }}" enctype="multipart/form-data">
                        @csrf

                        
                        <label for="fecha_consejo" class="col-form-label text-md-right">Fecha del consejo</label>
                        <input id="fecha_consejo" type="text" placeholder="dd/mm/aaaa" class="form-control datefield @error('fecha_consejo') is-invalid @enderror" name="fecha_consejo" value="{{ old('fecha_consejo') }}" required >
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
                        
                        <label for="tipo" class="text-left">Tipo de Sesión</label>
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
@push('head')
<script>
    window.addEventListener('load', function () {
        console.log('load');
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '< Ant',
            nextText: 'Sig >',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);

        $(".datefield").attr('readonly', 'readonly').attr('style', 'background-color:white').datepicker({
            dateFormat: "dd/mm/yy",
            language: 'es',
        });
    });
        
</script>


    
@endpush