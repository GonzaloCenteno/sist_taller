@extends('principal.p_logueo')

@section('content')

<div class="login-logo center-block">
    <center><h1><b>SISTEMA - TALLER</b></h1>
        <h2>INGRESAR CREDENCIALES PARA INICIAR SESION</h2></center>
</div>

<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    <div class="form-group{{ $errors->has('usuario') ? ' has-error' : '' }} has-feedback">
        <input type="text" name="usuario" class="form-control input-lg text-center" placeholder="INGRESAR NOMBRE DE USUARIO" autofocus="true" value="{{ old('usuario') }}" autocomplete="off">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        {!! $errors->first('usuario', '<span class="text-danger">:message</span>') !!}
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
        <input type="password" name="password" class="form-control input-lg text-center" placeholder="INGRESAR CONTRASEÑA">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        {!! $errors->first('password', '<span class="text-danger">:message</span>') !!}
    </div>

    <div class="row">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat btn-lg" style="background: #CC191C !important">INGRESAR</button>
        </div>
    </div>
    <div class="form-group">
        <h3 class="text-danger"><b>{!! Session::has('msg') ? Session::get("msg") : '' !!}</b></h3>
    </div>
</form>
@endsection
