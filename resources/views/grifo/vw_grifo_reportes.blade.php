@extends('principal.p_inicio')
@section('title', 'REPORTES') 
@section('content')
<style>
    /*.modal-lg { 
        max-width: 75% !important;
        padding-left: 50px;
    }    */
</style>
<br>
<div class="card card-danger card-outline">
    <div class="card-header align-self-center">
        <h4 class="m-0"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i> REPORTES</h4>
    </div>
    <div class="card-body">
        @if( $permiso[0]->btn_print == 1 )
            <div class="callout callout-danger">
                <div class="row ml-5">
                    <div class="col-md-2">
                        <button id="btn_consumo_xdia" class="btn btn-block btn-outline-danger"><i class="fa fa-download fa-2x" aria-hidden="true"></i> VER MAS</button>
                    </div>
                    <div class="col-md-10">
                        <h2>CONSUMO POR FECHAS</h2>
                    </div>
                </div>
            </div>
            <div class="callout callout-danger">
                <div class="row ml-5">
                    <div class="col-md-2">
                        <button id="btn_consumo_mensual" class="btn btn-block btn-outline-danger"><i class="fa fa-download fa-2x" aria-hidden="true"></i> VER MAS</button>
                    </div>
                    <div class="col-md-10">
                        <h2>CONSUMO MENSUAL</h2>
                    </div>
                </div>
            </div>
        @else
            <div class="callout callout-danger">
                <div class="row ml-5">
                    <div class="col-md-2">
                        <button onclick="sin_permiso();" class="btn btn-block btn-outline-danger"><i class="fa fa-download fa-2x" aria-hidden="true"></i> VER MAS</button>
                    </div>
                    <div class="col-md-10">
                        <h2>CONSUMO POR FECHAS</h2>
                    </div>
                </div>
            </div>
            <div class="callout callout-danger">
                <div class="row ml-5">
                    <div class="col-md-2">
                        <button onclick="sin_permiso();" class="btn btn-block btn-outline-danger"><i class="fa fa-download fa-2x" aria-hidden="true"></i> VER MAS</button>
                    </div>
                    <div class="col-md-10">
                        <h2>CONSUMO MENSUAL</h2>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div><!-- /.card -->

<div class="modal fade" id="ModalReporte2">
    <div class="modal-dialog modal-xs modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>FECHA INICIO:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="date" id="txt_fecha_inicio" class="form-control text-center">
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>FECHA FIN:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="date" id="txt_fecha_fin" class="form-control text-center">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_imprimir_reporte2" class="btn btn-primary btn-xl"></button>
                <button type="button" id="btn_cerrar_modal2" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/grifo/grifo_reportes.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('submenu');

    jQuery(document).ready(function ($) {

    });
</script>
@stop

@endsection


