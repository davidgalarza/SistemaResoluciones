@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Agregar Nueva Carrera</h3>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="/carreras" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nombre de la Carrera</label>
                            <input type="text" class="form-control" onkeypress="return validar(event)" name="nombre"
                                placeholder="Escribe el nombre de la nueva carrera">
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar</button>
                        <a href="/carreras">
                            <button type="button" class="btn btn-danger">Cancelar</button>
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function validar(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[A-Za-z\s]/; // 4
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
    }
</script>
@endsection