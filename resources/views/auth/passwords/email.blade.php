@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reiniciar Contraseña') }}</div>

                <div class="card-body">
                    <h5>Comun&iacute;quese con el administrador del sistema.</h5>
                    <button type="button" class="btn btn-warning" onclick="window.location = '{{ URL::asset('/login')}} '"><i class="fas fa-door"></i> Volver</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
