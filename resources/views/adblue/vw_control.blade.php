@extends('principal.p_inicio')

@section('content')
<style>
    /*.modal-lg { 
        max-width: 75% !important;
        padding-left: 50px;
    }    */
</style>
<br>
<div class="card card-danger card-outline">
    <div class="card-header">
        <h4 class="m-0">CONTROL DIARIO DE ADBLUE - AREQUIPA LITROS</h4>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="col-lg-12">
            <center>
                <button id="btn_nueva_estacion" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus-square"></i> CREAR ESTACION</button>
                <button id="btn_modificar_estacion" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR ESTACION</button></center>
        </div>
    </div>
</div><!-- /.card -->

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/control.js') }}"></script>
@stop

@endsection


