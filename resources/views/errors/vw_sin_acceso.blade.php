@extends('principal.p_inicio')

@section('content')
<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <img alt='#' src="{{ asset('img/404.png') }}" />
        <h1 class='mB-30 fw-900 lh-1 c-red-500' style="font-size: 60px;color:red;">NO PUEDES VER ESTA SECCION</h1>
        <h1 class='mB-30 fw-900 lh-1 c-red-500' style="font-size: 60px;color:red;">DEBES INICIAR SESION</h1>
        <h2 class='mB-10 fsz-lg c-grey-900 tt-c'>NO TIENE PERMISOS PARA VER ESTA SECCION</h2>
        <h3 class='mB-30 fw-900 lh-1 c-red-500' style="font-size: 60px;"><b>CONTACTARSE CON EL ADMINISTRADOR</b></h3>
        <div>
          <a href="{{ URL::to('/') }}" type='primary' class='btn btn-danger btn-lg btn-block'>REGRESAR A LA PAGINA DE INICIO</a>
        </div>
    </div>
</div>
@endsection