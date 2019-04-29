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
        <h4 class="m-0">COSTOS DE ADBLUE POR LITRO</h4>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="card-body" id="contenedors">
        <div class="form-row">
            <div class="col-lg-3 text-center">
                <div class="form-group">
                    <label>AÑO DE TRABAJO:</label>

                    <select class="form-control" style="width: 100%;" id="cbx_coa_anio" name="cbx_coa_anio">
                        <option value="2019">.:: 2019 ::.</option>
                        <option value="2020">.:: 2020 ::.</option>
                        <option value="2021">.:: 2021 ::.</option>
                        <option value="2022">.:: 2022 ::.</option>
                        <option value="2023">.:: 2023 ::.</option>
                    </select>

                </div>
            </div>
            <div class="col-lg-9 text-left" style="padding-top: 32px;">
                <button id="btn_nuevo_costo_adblue" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus-square"></i> NUEVO COSTO</button>
                <button id="btn_modificar_costo_adblue" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR COSTO</button>
            </div>
        </div>
        <br>
        <div class="form-group col-md-12">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblcostos_adblue"></table>
                <div id="paginador_tblcostos_adblue"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

<div class="modal fade" id="ModalCostosAdblue">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>AÑO:</label>

                            <select class="form-control" style="width: 100%;" id="cbx_coa_anio_mdl" name="cbx_coa_anio_mdl" disabled="">
                                <option value="2019"> 2019 </option>
                                <option value="2020"> 2020 </option>
                                <option value="2021"> 2021 </option>
                                <option value="2022"> 2022 </option>
                                <option value="2023"> 2023 </option>
                            </select>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>MES:</label>

                            <select class="form-control" style="width: 100%;" id="cbx_coa_mes_mdl" name="cbx_coa_mes_mdl">
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

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>VALOR COSTO:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                </div>
                                <input type="text" id="txt_coa_costo" class="form-control text-center" placeholder="ESCRIBIR COSTO" onkeypress="return soloNumeroTab(event);" maxlength="8">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_crear_costo_adblue" class="btn btn-primary btn-xl"></button>
                <button type="button" id="btn_actualizar_costo_adblue" class="btn btn-warning btn-xl"></button>
                <button type="button" id="btn_cerrar_modal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/costo_adblue.js') }}"></script>
@stop

@endsection


