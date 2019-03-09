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
        <h4 class="m-0">REGISTRO DE RUTA - ESTACIONES</h4>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="col-lg-12">
            <center>
                <div id="listadoButtons">
                    <button id="btn_vw_rtestacion_Nuevo" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus"></i> NUEVOS REGISTROS</button>
                </div>

                <div id="formularioButtons" style="display:none;">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">

                                <div class="input-group">
                                    <h4 id_ruta="" id="lbl_rte_ruta_create"></h4>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <button id="btn_generar_estaciones" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus-square"> GENERAR ESTACIONES</i></button>
                            <button id="btn_vw_rtestacion_Cancelar" type="button" class="btn btn-xl btn-default" readonly="readonly"><i class="fa fa-arrow-circle-left"></i> REGRESAR</button>
                        </div>
                    </div>
                </div> 
            </center>
        </div>

        <div class="col-xs-12 center-block" id="formularioRegistros" style="display: none;">
            <form id="FormularioRtesaciones" name="FormularioRtesaciones" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <table class="table table-striped table-bordered table-condensed table-hover">
                            <thead style="background-color:#A9D0F5">
                            <th style="width: 10%;">BORRAR</th>
                            <th style="width: 70%;">ESTACION</th>
                            <th style="width: 20%;">CONSUMO</th>
                            </thead>
                            <tbody id="cuerpo_rtestaciones">

                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <div style="padding-left: 10px">
                <button id="btn_vw_rtestacion_Guardar" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-save"></i> GUARDAR REGISTROS</button>
            </div>
        </div>

        <br>
        <div class="form-group col-md-12" id="listadoRegistros">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblrutaestacion"></table>
                <div id="paginador_tblrutaestacion"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

<div class="modal fade" id="ModalRutaEstacion">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">

                            <div class="input-group">
                                <h4 rte_id="" id="lbl_rte_ruta"> </h4>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="m-0">DATOS ESTACION</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ESTACION:</label>

                                    <select class="form-control select2" style="width: 100%;" id="txt_rte_estacion">
                                        @foreach($estaciones as $est)
                                        <option value="{{ $est->est_id }}"> {{ $est->est_descripcion }} </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>CONSUMO:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="text" id="txt_rte_consumo" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="btn_actualizar_rtestacion" class="btn btn-warning btn-xl"></button>
                <button type="button" id="btn_cerrar_modal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/ruta_estacion.js') }}"></script>
@stop

@endsection


