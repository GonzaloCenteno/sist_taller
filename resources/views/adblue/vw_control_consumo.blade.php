@extends('principal.p_inicio')

@section('content')
<style>

    th.ui-th-column div{
    white-space:normal !important;
    height:auto !important;
    padding:2px;
}

</style>
<br>
<div class="card card-danger card-outline">
    <div class="card-header">
        <h4 class="m-0">CONTROL CONSUMO - AREQUIPA</h4>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-lg-2">
                <div class="form-group">
                    <label>AÑO:</label>

                    <select class="form-control" style="width: 100%;" id="cbx_cde_anio" name="cbx_cde_anio">
                        <option value="2019"> 2019 </option>
                        <option value="2020"> 2020 </option>
                        <option value="2021"> 2021 </option>
                        <option value="2022"> 2022 </option>
                        <option value="2023"> 2023 </option>
                    </select>

                </div>
            </div>
            
            <div class="col-lg-2">
                <div class="form-group">
                    <label>MES:</label>

                    <select class="form-control" style="width: 100%;" id="cbx_cde_mes" name="cbx_cde_mes">
                        <option value="1"> ENERO </option>
                        <option value="2"> FEBRERO </option>
                        <option value="3"> MARZO </option>
                        <option value="4"> ABRIL </option>
                        <option value="5"> MAYO </option>
                        <option value="6"> JUNIO </option>
                        <option value="7"> JULIO </option>
                        <option value="8"> AGOSTO </option>
                        <option value="9"> SEPTIEMBRE </option>
                        <option value="10"> OCTUBRE </option>
                        <option value="11"> NOVIEMBRE </option>
                        <option value="12"> DICIEMBRE </option>
                    </select>

                </div>
            </div>
            
            <div class="col-lg-2">
                <div class="form-group">
                    <label>ESCRIBIR N° PLACA:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-th-list"></i></span>
                        </div>
                        <input type="text" id="txt_cde_placa" class="form-control text-center text-uppercase">
                    </div>
                </div>
            </div>
            <div class="col-lg-1" style="padding-top: 32px;">
                <button id="btn_vw_buscar_placa" type="button" class="btn btn-xl btn-success" readonly="readonly"><i class="fa fa-search"></i> BUSCAR</button>
            </div>
            <div class="col-lg-1" style="padding-top: 32px;">
                <button id="btn_vw_nuevos_condet" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-plus-square"></i> AGREGAR DETALLE</button>
            </div>
        </div>

        <div class="form-group col-md-12">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblcontrol_consumo"></table>
                <div id="paginador_tblcontrol_consumo"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

<div class="modal fade" id="ModalConsumoQabastecida">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">

                            <div class="input-group text-center">
                                <center><h4 cde_id="" id="lbl_cde_consumo" class="text-center"> </h4></center>
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>CONSUMO:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_qabastecida" class="form-control text-center" maxlength="8" onkeypress="return soloNumeroTab(event);">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="btn_actualizar_conest" class="btn btn-warning btn-xl"></button>
                <button type="button" id="btn_cerrar_modal_1" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalConsumoDetalle">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">

                            <div class="input-group text-center">
                                <center><h4 xcde_id="" id="lbl_cde_consumodet" class="text-center"> </h4></center>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="m-0">DATOS DETALLE CONSUMO</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>TOTAL CONSUMO DESEADO:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_condeseado" class="form-control text-center" maxlength="8" onkeypress="return soloNumeroTab(event);">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>MONTO OPTIMO ABASTECER AQP:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_montoptimo" class="form-control text-center" maxlength="8" onkeypress="return soloNumeroTab(event);">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="btn_actualizar_condet" class="btn btn-warning btn-xl"></button>
                <button type="button" id="btn_cerrar_modal_2" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/control_consumo.js') }}"></script>
@stop

@endsection


