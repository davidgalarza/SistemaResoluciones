@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Consejos</h3>
                    @can('ABOGADO')
                        <div class="float-right">
                            <a href="/consejos/nuevo">
                                <button type="button" class="btn btn-primary">
                                    <i class="fas fa-folder-plus"></i> Nueva Consejo
                                </button>
                            </a>
                        </div>
                    @endcan
                    
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
                    

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
