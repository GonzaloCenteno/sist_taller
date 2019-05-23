@extends('principal.p_inicio')
@section('title', 'CTRL - CONSUMO')
@section('content')
<style>

    th.ui-th-column div{
        white-space:normal !important;
        height:auto !important;
        padding:2px;
    }
    
    .costo_cdg {
        background-color: rgba(56 , 86, 36,0.5);
    }
    #jqgh_tblcost_opt_ruta_costocdg{
        background-color: rgba(56 , 86, 36,0.5);
    }
    
    .costo_cra {
        background-color: rgba(191 , 143, 0,0.5);
    }
    #jqgh_tblcost_opt_ruta_costocra{
        background-color: rgba(191 , 143, 0,0.5);
    }
    
    .costo_ae {
        background-color: rgba(197 , 89, 17,0.5);
    }
    #jqgh_tblcost_opt_ruta_costoae{
        background-color: rgba(197 , 89, 17,0.5);
    }
    
    #jqgh_tblcost_gen_abast_ruta_totalca{
        background-color: rgba(56 , 86, 36,0.5);
    }
    
    #jqgh_tblcost_gen_abast_ruta_totalce{
        background-color: rgba(191 , 143, 0,0.5);
    }
    
    #jqgh_tblcost_gen_abast_ruta_totalcae{
        background-color: rgba(197 , 89, 17,0.5);
    }
    
    #jqgh_tblcost_gen_abast_placa_costo_ahorro{
        background-color: rgba(56 , 86, 36,0.5);
    }
    
    #jqgh_tblcost_gen_abast_placa_costo_exceso{
        background-color: rgba(191 , 143, 0,0.5);
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
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-control-consumo-tab" data-toggle="tab" href="#nav-control-consumo" role="tab" aria-controls="nav-control-consumo" aria-selected="true">CONTROL CONSUMO</a>
                <a class="nav-item nav-link" id="nav-promedio-general-tab" data-toggle="tab" href="#nav-promedio-general" role="tab" aria-controls="nav-promedio-general" aria-selected="false">DATOS PROMEDIO / GENERAL</a>
                <a class="nav-item nav-link" id="nav-costos-tab" data-toggle="tab" href="#nav-costos" role="tab" aria-controls="nav-costos" aria-selected="false">COSTOS ABASTECIMIENTO</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <br>
            <div class="tab-pane fade show active" id="nav-control-consumo" role="tabpanel" aria-labelledby="nav-control-consumo-tab">
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
                    <div class="col-lg-3" style="padding-top: 32px;">
                        <button id="btn_vw_buscar_placa" type="button" class="btn btn-xl btn-success" readonly="readonly"><i class="fa fa-search"></i> BUSCAR</button>
                    </div>
                    @if( $permiso[0]->btn_new == 1 )
                        <div class="col-lg-3" style="padding-top: 32px;">
                            <button id="btn_vw_nuevos_condet" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-plus-square"></i> AGREGAR / MODIFICAR DETALLE</button>
                        </div>
                    @else
                        <div class="col-lg-3" style="padding-top: 32px;">
                            <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-plus-square"></i> AGREGAR / MODIFICAR DETALLE</button>
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <div class="form-group col-md-12" id="contenedor">
                        <table id="tblcontrol_consumo"></table>
                        <div id="paginador_tblcontrol_consumo"></div>                         
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-promedio-general" role="tabpanel" aria-labelledby="nav-promedio-general-tab">
                <div class="form-row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>AÑO:</label>

                            <select class="form-control" style="width: 100%;" id="cbx_dat_anio" name="cbx_dat_anio">
                                <option value="2019"> 2019 </option>
                                <option value="2020"> 2020 </option>
                                <option value="2021"> 2021 </option>
                                <option value="2022"> 2022 </option>
                                <option value="2023"> 2023 </option>
                            </select>

                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>MES:</label>

                            <select class="form-control" style="width: 100%;" id="cbx_dat_mes" name="cbx_dat_mes">
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
                </div>

                <div class="form-group col-md-12">
                    <div class="form-group col-md-12" id="contenedor">
                        <table id="tblprom_gen_rut"></table>
                        <div id="paginador_tblprom_gen_rut">
                            <div style="float: right; font-weight: bold;">
                                TOTAL: <input type="text" id="total_viajes_rut" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;" readonly="">
                            </div>
                        </div>                         
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6" id="contenedor">
                        <table id="tblgen_scania"></table>
                        <div id="paginador_tblgen_scania">
                            <div style="float: right; font-weight: bold;">
                                TOTAL: <input type="text" id="total_viajes_scania" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;" readonly="">
                            </div>
                        </div>                         
                    </div>

                    <div class="form-group col-md-6" id="contenedor">
                        <table id="tblprom_gen_irizar"></table>
                        <div id="paginador_tblprom_gen_irizar">
                            <div style="float: right; font-weight: bold;">
                                TOTAL: <input type="text" id="total_viajes_irizar" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;" readonly="">
                            </div>
                        </div>                         
                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="nav-costos" role="tabpanel" aria-labelledby="nav-costos-tab">
                <div class="form-row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>AÑO:</label>

                            <select class="form-control" style="width: 100%;" id="cbx_cost_anio" name="cbx_cost_anio">
                                <option value="2019"> 2019 </option>
                                <option value="2020"> 2020 </option>
                                <option value="2021"> 2021 </option>
                                <option value="2022"> 2022 </option>
                                <option value="2023"> 2023 </option>
                            </select>

                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>MES:</label>

                            <select class="form-control" style="width: 100%;" id="cbx_cost_mes" name="cbx_cost_mes">
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

                    <div class="col-lg-3 text-center">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="row">
                                    <label>COSTO DE ADBLUE POR LITRO (+IGV):</label>
                                    <label type="text" id="txt_coa_saldo" class="form-control"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if( $permiso[0]->btn_print == 1 )
                        <div class="col-lg-3 text-center" style="padding-top: 32px;">
                            <button id="btn_imprimir_informacion_excel" type="button" class="btn btn-xl btn-success"><i class="fa fa-download"></i> DESCARGAR INFORMACION</button>
                        </div>
                    @else
                        <div class="col-lg-3 text-center" style="padding-top: 32px;">
                            <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-success"><i class="fa fa-download"></i> DESCARGAR INFORMACION</button>
                        </div>
                    @endif
                    
                </div>
                
                <div class="form-group col-md-12">
                    <div class="form-group col-md-12" id="contenedor">
                        <table id="tblcost_opt_ruta"></table>
                        <div id="paginador_tblcost_opt_ruta" class="row">
                            <div style="font-weight: bold;" class="col-md-4 text-center">
                                COSTO TOTAL SEGUN PARAMETROS ESTABLECIDOS: <input type="text" id="total_cdg" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;background-color: rgba(56 , 86, 36,0.5);" readonly="">
                            </div>
                            <div style="font-weight: bold;" class="col-md-4 text-center">
                                COSTO TOTAL AHORRO/EXCESO SEGUN PARAMETROS: <input type="text" id="total_cra" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;background-color: rgba(191 , 143, 0,0.5);" readonly="">
                            </div>
                            <div style="font-weight: bold;" class="col-md-4 text-center">
                                COSTO TOTAL OPTIMO AHORRO/EXCESO: <input type="text" id="total_cae" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;background-color: rgba(197 , 89, 17,0.5);" readonly="">
                            </div>
                        </div>                         
                    </div>
                </div>
                
                <div class="form-group col-md-12">
                    <div class="form-group col-md-12" id="contenedor">
                        <table id="tblcost_gen_abast_ruta"></table>
                        <div id="paginador_tblcost_gen_abast_ruta" class="row">
                            <div style="font-weight: bold;" class="col-md-4 text-center">
                                COSTO TOTAL DE AHORRO: <input type="text" id="total_cta" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;background-color: rgba(56 , 86, 36,0.5);" readonly="">
                            </div>
                            <div style="font-weight: bold;" class="col-md-4 text-center">
                                COSTO TOTAL DE EXCESO: <input type="text" id="total_cte" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;background-color: rgba(191 , 143, 0,0.5);" readonly="">
                            </div>
                            <div style="font-weight: bold;" class="col-md-4 text-center">
                                COSTO TOTAL DE AHORRO / EXCESO: <input type="text" id="total_ctae" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;background-color: rgba(197 , 89, 17,0.5);" readonly="">
                            </div>
                        </div>                         
                    </div>
                </div>
                
                <div class="form-group col-md-12">
                    <div class="form-group col-md-12" id="contenedor">
                        <table id="tblcost_gen_abast_placa"></table>
                        <div id="paginador_tblcost_gen_abast_placa" class="row">
                            <div style="font-weight: bold;" class="col-md-4 text-center">
                                COSTO TOTAL DE AHORRO: <input type="text" id="total_cta_placa" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;background-color: rgba(56 , 86, 36,0.5);" readonly="">
                            </div>
                            <div style="font-weight: bold;" class="col-md-4 text-center">
                                COSTO TOTAL DE EXCESO: <input type="text" id="total_cte_placa" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;background-color: rgba(191 , 143, 0,0.5);" readonly="">
                            </div>
                            <div style="font-weight: bold;" class="col-md-4 text-center">
                                COSTO TOTAL DE AHORRO / EXCESO: <input type="text" id="total_ctae_placa" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;background-color: rgba(242, 38, 19, 1);" readonly="">
                            </div>
                        </div>                         
                    </div>
                </div>
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
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="m-0">DATOS ESTACION</h5>
                            </div>
                            <div class="col-md-6 text-center" id="cabeceraConsumoQabastecida">
                                <button type="button" id="btn_modificar_txt_qparcial" class="btn btn-danger btn-xl"><i class="fa fa-pencil-square-o"></i> MODIFICAR Q-ABAST.</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Q-ABAST.:</label>

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
                <button type="button" id="btn_actualizar_conest" class="btn btn-warning btn-xl"><i class="fa fa-pencil-square-o"></i> MODIFICAR</button>
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

<!-- MODAL CONSUMO PARCIAL -->

<div class="modal fade" id="ModalConsumoQparcial">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group text-center">

                            <div class="input-group">
                                <h4 id="lbl_conpar_placa"></h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group text-center">

                            <div class="input-group">
                                <h4 id="lbl_conpar_ruta"></h4>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="m-0">NUEVOS Q-ABAST. PARCIALES</h5>
                    </div>

                    <div class="card-body">
                        <form id="FormularioQabastParcialMod" name="FormularioQabastParcialMod" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row" id="detalle_qabast_parcial">
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="btn_actualizar_cqparciales" class="btn btn-success btn-xl"><i class="fa fa-pencil-square-o"></i> ACTUALIZAR Q-ABAST.</button>
                <button type="button" id="btn_cerrar_modal_3" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalReportesGeneral">
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
                            <label>SELECCIONAR COLUMNA:</label>

                            <select class="form-control" style="width: 100%;" id="cbx_rep_columna" name="cbx_rep_columna">
                                
                            </select>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>ORDEN:</label>

                            <select class="form-control" style="width: 100%;" id="cbx_rep_orden" name="cbx_rep_orden">
                                <option value="ASC">..:: ASCENDENTE ::.. </option>
                                <option value="DESC">..:: DESCENDENTE ::..</option>
                            </select>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" id="ModalReportesGeneralFooter"></div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/control_consumo.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('active');
    
    jQuery(document).ready(function ($) {
        miFechaActual = new Date();
        mes = parseInt(miFechaActual.getMonth()) + 1;
        anio = miFechaActual.getFullYear();
        $("#cbx_cde_mes").val(mes);
        $("#cbx_cde_anio").val(anio);

        $("#cbx_dat_mes").val(mes);
        $("#cbx_dat_anio").val(anio);

        $("#cbx_cost_mes").val(mes);
        $("#cbx_cost_anio").val(anio);

        jQuery("#tblcontrol_consumo").jqGrid({
            url: 'control_consumo/0?grid=control_consumo&mes='+mes+'&anio='+anio,
            datatype: 'json', mtype: 'GET',
            height: '340px', autowidth: true,
            toolbarfilter: true,
            sortable: false,
            shrinkToFit: false,
            forceFit:true,  
            scroll: false,
            colNames: ['CCA_ID', 'CDE_ID', 'FECHA', 'PLACA', 'CONDUCTOR', 'COPILOTO', 'RUTA', 'Q - ABAST.', 'TOTAL CONSUMO REAL', 'TOTAL CONSUMO DESEADO', 'AH. - EX. CONSUMO TOTAL','MONTO OPTIMO ABASTECER','AHORRO EXCESO GRAL','AHORRO POR VIAJE','EXCESO POR VIAJE','KM.I','KM.F','KILOMETRAJE','RENDIMIENTO KM/LT','RENDIMIENTO KM/GL'],
            rowNum: 20, sortname: 'xcde_placa', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblcontrol_consumo" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - CONTROL CONSUMO AREQUIPA -', align: "center",
            colModel: [
                {name: 'xcca_id', index: 'xcca_id', align: 'left', width: 10, hidden: true,frozen:true},
                {name: 'xcde_id', index: 'xcde_id', align: 'left', width: 10, hidden: true,frozen:true},
                {name: 'xcde_fecha', index: 'xcde_fecha', align: 'center', width: 100, formatter: 'date', formatoptions: {srcformat: 'Y-m-d', newformat: 'd/m'},frozen:true},
                {name: 'xcde_placa', index: 'xcde_placa', align: 'center', width: 100,frozen:true},
                {name: 'xcde_conductor', index: 'xcde_conductor', align: 'left', width: 250},
                {name: 'xcde_copiloto', index: 'xcde_copiloto', align: 'left', width: 250},
                {name: 'xcde_ruta', index: 'xcde_ruta', align: 'center', width: 150},
                {name: 'xcde_qabastecida', index: 'xcde_qabastecida', align: 'center', width: 150},
                {name: 'xcde_consumo_real', index: 'xcde_consumo_real', align: 'center', width: 100},
                {name: 'xcde_consumo_deseado', index: 'xcde_consumo_deseado', align: 'center', width: 100},
                {name: 'xcde_ahorro_exceso_com', index: 'xcde_ahorro_exceso_com', align: 'center', width: 100,formatter: FormatoNumeros},
                {name: 'xcde_montoptimo', index: 'xcde_montoptimo', align: 'center', width: 100},
                {name: 'xcde_ahex_gral', index: 'xcde_ahex_gral', align: 'center', width: 100},
                {name: 'xcde_ahxviaje', index: 'xcde_ahxviaje', align: 'center', width: 100},
                {name: 'xcde_exxviaje', index: 'xcde_exxviaje', align: 'center', width: 100,formatter: FormatoNumeros},
                {name: 'xcde_kmi', index: 'xcde_kmi', align: 'center', width: 100},
                {name: 'xcde_kmf', index: 'xcde_kmf', align: 'center', width: 100},
                {name: 'xcde_kilometraje', index: 'xcde_kilometraje', align: 'center', width: 100},
                {name: 'xcde_rendimiento_lt', index: 'xcde_rendimiento_lt', align: 'center', width: 100},
                {name: 'xcde_rendimiento_gl', index: 'xcde_rendimiento_gl', align: 'center', width: 100},
            ],
            pager: '#paginador_tblcontrol_consumo',
            rowList: [10, 20, 30, 40, 50],
            subGrid: true,
            subGridRowExpanded: mostrar_estaciones,
            subGridOptions : {
                reloadOnExpand :false,
                selectOnExpand : true 
            },
            gridComplete: function () {
                var idarray = jQuery('#tblcontrol_consumo').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                var firstid = jQuery('#tblcontrol_consumo').jqGrid('getDataIDs')[0];
                    $("#tblcontrol_consumo").setSelection(firstid);    
                }
            },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });

        $('#tblcontrol_consumo').setGroupHeaders(
        {
            useColSpanStyle: true,
            groupHeaders: [
                { "numberOfColumns": 4, "titleText": "<center><h5>RESUMEN</h5></center>", "startColumnName": "xcde_conductor",align: 'center'},
                { "numberOfColumns": 7, "titleText": "<center><h5>CONSUMOS, AHORROS - EXCESOS</h5></center>", "startColumnName": "xcde_consumo_real",align: 'center' },
                { "numberOfColumns": 5, "titleText": "<center><h5>KILOMETRAJES</h5></center>", "startColumnName": "xcde_kmi",align: 'center' }]
        });

        $("#tblcontrol_consumo").jqGrid("destroyFrozenColumns")
                .jqGrid("setColProp", "xcca_id", { frozen: true })
                .jqGrid("setColProp", "xcde_id", { frozen: true })
                .jqGrid("setColProp", "xcde_fecha", { frozen: true })
                .jqGrid("setColProp", "xcde_placa", { frozen: true })
                .jqGrid("setFrozenColumns")
                .trigger("reloadGrid", [{ current: true}]);

        $("#txt_cde_placa").keypress(function (e) {
            if (e.which == 13) {
                buscar_placas();
            }
        });

        //DATOS PROMEDIOS GENERALES RUTAS

        jQuery("#tblprom_gen_rut").jqGrid({
            url: 'control_consumo/0?grid=prom_gen_rut&mes='+mes+'&anio='+anio,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            cmTemplate: { sortable: false },
            colNames: ['RUTA', 'CONSUMO','KILOMETRAJE','RENDIMIENTO','AHORRO','EXCESO','TOTAL A/E','N° VIAJES'],
            rowNum: 100, sortname: 'xcde_ruta', sortorder: 'asc', align: "center",
            colModel: [
                {name: 'xcde_ruta', index: 'xcde_ruta', align: 'left',width:150,frozen:true},
                {name: 'consumo', index: 'consumo', align: 'center', width: 170},
                {name: 'kg', index: 'kg', align: 'center', width: 170},
                {name: 'rendimiento', index: 'rendimiento', align: 'center', width: 170},
                {name: 'ahorro', index: 'ahorro', align: 'center', width: 170},
                {name: 'exceso', index: 'exceso', align: 'center', width: 170},
                {name: 'totalae', index: 'totalae', align: 'center', width: 170},
                {name: 'nro_viajes', index: 'nro_viajes', align: 'center', width: 100}
            ],
            rownumbers: true,
            rownumWidth: 25,
            loadComplete: function () {
                var sum = jQuery("#tblprom_gen_rut").getGridParam('userData').sum;
                if(sum==undefined){
                    $("#total_viajes_rut").val('0');
                }else{
                    $("#total_viajes_rut").val(sum);
                }
            },
            pager: '#paginador_tblprom_gen_rut',
            rowList: [],       
            pgbuttons: false,     
            pgtext: null,       
            viewrecords: false
        });

        $('#tblprom_gen_rut').setGroupHeaders(
        {
            useColSpanStyle: true,
            groupHeaders: [
                { "numberOfColumns": 7, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>DATOS PROMEDIOS GENERALES POR TODA LA RUTA</h5></div><div class='col-md-4'><button class='btn btn-warning' @if($permiso[0]->btn_print == 1) onClick='traer_datos_desplegable(tblprom_gen_rut,1);' @else onClick='sin_permiso();' @endif><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "consumo",align: 'center'}]
        });

        //DATOS GENERALES POR PLACA - SCANIA

        jQuery("#tblgen_scania").jqGrid({
            url: 'control_consumo/0?grid=dat_gen_escania&mes='+mes+'&anio='+anio,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            cmTemplate: { sortable: false },
            colNames: ['SCANIA', 'RENDIMIENTO','AHORRO','EXCESO','TOTAL A/E','N° VIAJES'],
            rowNum: 100, sortname: 'xcde_placa', sortorder: 'asc', viewrecords: false, align: "center",
            colModel: [
                {name: 'xcde_placa', index: 'xcde_placa', align: 'left',width: 100,frozen:true},
                {name: 'rendimiento', index: 'rendimiento', align: 'center', width: 120},
                {name: 'ahorro', index: 'ahorro', align: 'center', width: 100},
                {name: 'exceso', index: 'exceso', align: 'center', width: 100},
                {name: 'totalae', index: 'totalae', align: 'center', width: 100},
                {name: 'nro_viajes', index: 'nro_viajes', align: 'center', width: 100}
            ],
            rownumbers: true, // show row numbers
            rownumWidth: 25, // the width of the row numbers columns
            loadComplete: function () {
                var sum = jQuery("#tblgen_scania").getGridParam('userData').sum;
                if(sum==undefined){
                    $("#total_viajes_scania").val('0');
                }else{
                    $("#total_viajes_scania").val(sum);
                }
            },
            pager: '#paginador_tblgen_scania',
            rowList: [],       
            pgbuttons: false,     
            pgtext: null,       
        });

        $('#tblgen_scania').setGroupHeaders(
        {
            useColSpanStyle: true,
            groupHeaders: [
                { "numberOfColumns": 5, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>DATOS GENERALES POR PLACA</h5></div><div class='col-md-4'><button class='btn btn-warning' @if($permiso[0]->btn_print == 1) onClick='traer_datos_desplegable(tblgen_scania,2);' @else onClick='sin_permiso();' @endif><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "rendimiento",align: 'center'}]
        });

        //DATOS GENERALES POR PLACA - IRIZAR

        jQuery("#tblprom_gen_irizar").jqGrid({
            url: 'control_consumo/0?grid=dat_gen_irizar&mes='+mes+'&anio='+anio,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            cmTemplate: { sortable: false },
            colNames: ['IRIZAR', 'RENDIMIENTO','AHORRO','EXCESO','TOTAL A/E','N° VIAJES'],
            rowNum: 100, sortname: 'xcde_placa', sortorder: 'asc', viewrecords: false, align: "center",
            colModel: [
                {name: 'xcde_placa', index: 'xcde_placa', align: 'left',width: 100,frozen:true},
                {name: 'rendimiento', index: 'rendimiento', align: 'center', width: 120},
                {name: 'ahorro', index: 'ahorro', align: 'center', width: 100},
                {name: 'exceso', index: 'exceso', align: 'center', width: 100},
                {name: 'totalae', index: 'totalae', align: 'center', width: 100},
                {name: 'nro_viajes', index: 'nro_viajes', align: 'center', width: 100}
            ],
            rownumbers: true, // show row numbers
            rownumWidth: 25, // the width of the row numbers columns
            loadComplete: function () {
                var sum = jQuery("#tblprom_gen_irizar").getGridParam('userData').sum;
                if(sum==undefined){
                    $("#total_viajes_irizar").val('0');
                }else{
                    $("#total_viajes_irizar").val(sum);
                }
            },
            pager: '#paginador_tblprom_gen_irizar',
            rowList: [],       
            pgbuttons: false,     
            pgtext: null
        });

        $('#tblprom_gen_irizar').setGroupHeaders(
        {
            useColSpanStyle: true,
            groupHeaders: [
                { "numberOfColumns": 5, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>DATOS GENERALES POR PLACA</h5></div><div class='col-md-4'><button class='btn btn-warning' @if($permiso[0]->btn_print == 1) onClick='traer_datos_desplegable(tblprom_gen_irizar,3);' @else onClick='sin_permiso();' @endif><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "rendimiento",align: 'center'}]
        });


        //COSTO OPTIMO GENERAL POR ABASTECIMIENTO EN RUTA

        jQuery("#tblcost_opt_ruta").jqGrid({
            url: 'control_consumo/0?grid=cost_opt_ruta&mes='+mes+'&anio='+anio,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            cmTemplate: { sortable: false },
            colNames: ['RUTA', 'CONSUMO DESEADO GENERAL','CONSUMO REAL AREQUIPA','TOTAL A/E','COSTO CDG','COSTO CRA','COSTO A/E'],
            rowNum: 100, sortname: 'xrut_id', sortorder: 'asc', viewrecords: false, align: "center",
            colModel: [
                {name: 'xcde_ruta', index: 'xcde_ruta', align: 'left',width: 150,frozen:true},
                {name: 'cdg', index: 'cdg', align: 'center', width: 200},
                {name: 'cra', index: 'cra', align: 'center', width: 200},
                {name: 'totalae', index: 'totalae', align: 'center', width: 160},
                {name: 'costocdg', index: 'costocdg', align: 'center', width: 160,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cdg'},
                {name: 'costocra', index: 'costocra', align: 'center', width: 160,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cra'},
                {name: 'costoae', index: 'costoae', align: 'center', width: 160,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_ae'}
            ],
            rownumbers: true, // show row numbers
            rownumWidth: 25, // the width of the row numbers columns
            loadComplete: function () {
                var $self = $(this);
                var total_costocdg = $self.jqGrid("getCol", "costocdg", false, "sum");
                var total_costocra = $self.jqGrid("getCol", "costocra", false, "sum");
                var total_costoae = $self.jqGrid("getCol", "costoae", false, "sum");
                $("#total_cdg").val("S/."+ Number(total_costocdg.toFixed(3)));
                $("#total_cra").val("S/."+ Number(total_costocra.toFixed(3)));
                $("#total_cae").val("S/."+ Number(total_costoae.toFixed(3)));
            },
            pager: '#paginador_tblcost_opt_ruta',
            rowList: [],       
            pgbuttons: false,     
            pgtext: null
        });

        $('#tblcost_opt_ruta').setGroupHeaders(
        {
            useColSpanStyle: true,
            groupHeaders: [
                { "numberOfColumns": 6, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>COSTO OPTIMO GENERAL POR ABASTECIMIENTO EN  RUTA</h5></div><div class='col-md-4'><button class='btn btn-warning' @if($permiso[0]->btn_print == 1) onClick='traer_datos_desplegable(tblcost_opt_ruta,4);' @else onClick='sin_permiso();' @endif><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "cdg",align: 'center'}]
        });

        //COSTO GENERAL  POR ABASTECIMIENTO EN RUTA

        jQuery("#tblcost_gen_abast_ruta").jqGrid({
            url: 'control_consumo/0?grid=cost_gen_abast_ruta&mes='+mes+'&anio='+anio,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            cmTemplate: { sortable: false },
            colNames: ['RUTA', 'AHORRO','TOTAL C/A','EXCESO','TOTAL C/E','TOTAL C/AE'],
            rowNum: 100, sortname: 'xcde_ruta', sortorder: 'asc', viewrecords: false, align: "center",
            colModel: [
                {name: 'xcde_ruta', index: 'xcde_ruta', align: 'left',width: 200,frozen:true},
                {name: 'ahorro', index: 'ahorro', align: 'center', width: 200},
                {name: 'totalca', index: 'totalca', align: 'center', width: 200,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cdg'},
                {name: 'exceso', index: 'exceso', align: 'center', width: 200},
                {name: 'totalce', index: 'totalce', align: 'center', width: 200,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cra'},
                {name: 'totalcae', index: 'totalcae', align: 'center', width: 200,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_ae'}
            ],
            rownumbers: true,
            rownumWidth: 25, 
            loadComplete: function () {
                var $self = $(this);
                var total_cta = $self.jqGrid("getCol", "totalca", false, "sum");
                var total_cte = $self.jqGrid("getCol", "totalce", false, "sum");
                var total_ctae = $self.jqGrid("getCol", "totalcae", false, "sum");
                $("#total_cta").val("S/."+ Number(total_cta.toFixed(3)));
                $("#total_cte").val("S/."+ Number(total_cte.toFixed(3)));
                $("#total_ctae").val("S/."+ Number(total_ctae.toFixed(3)));
            },
            pager: '#paginador_tblcost_gen_abast_ruta',
            rowList: [],       
            pgbuttons: false,     
            pgtext: null
        });

        $('#tblcost_gen_abast_ruta').setGroupHeaders(
        {
            useColSpanStyle: true,
            groupHeaders: [
                { "numberOfColumns": 5, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>COSTO GENERAL  POR ABASTECIMIENTO EN RUTA</h5></div><div class='col-md-4'><button class='btn btn-warning' @if($permiso[0]->btn_print == 1) onClick='traer_datos_desplegable(tblcost_gen_abast_ruta,5);' @else onClick='sin_permiso();' @endif><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "ahorro",align: 'center'}]
        });

        //COSTO GENERAL POR ABASTECIMIENTO POR PLACA

        jQuery("#tblcost_gen_abast_placa").jqGrid({
            url: 'control_consumo/0?grid=cost_gen_abast_placa&mes='+mes+'&anio='+anio,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            cmTemplate: { sortable: false },
            colNames: ['PLACA', 'AHORRO','COSTO AHORRO','EXCESO','COSTO EXCESO'],
            rowNum: 100, sortname: 'xcde_placa', sortorder: 'asc', viewrecords: false, align: "center",
            colModel: [
                {name: 'xcde_placa', index: 'xcde_placa', align: 'left',width: 200,frozen:true},
                {name: 'ahorro', index: 'ahorro', align: 'center', width: 250},
                {name: 'costo_ahorro', index: 'costo_ahorro', align: 'center', width: 250,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cdg'},
                {name: 'exceso', index: 'exceso', align: 'center', width: 250,formatter: FormatoNumeros},
                {name: 'costo_exceso', index: 'costo_exceso', align: 'center', width: 250,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cra'}
            ],
            rownumbers: true,
            rownumWidth: 25, 
            loadComplete: function () {
                var $self = $(this);
                var total_cta_placa = $self.jqGrid("getCol", "costo_ahorro", false, "sum");
                var total_cte_placa = $self.jqGrid("getCol", "costo_exceso", false, "sum");
                var total = total_cta_placa + total_cte_placa;
                $("#total_cta_placa").val("S/."+ Number(total_cta_placa.toFixed(3)));
                $("#total_cte_placa").val("S/."+ Number(total_cte_placa.toFixed(3)));
                $("#total_ctae_placa").val("S/."+ Number(total.toFixed(3)));
            },
            pager: '#paginador_tblcost_gen_abast_placa',
            rowList: [],       
            pgbuttons: false,     
            pgtext: null
        });

        $('#tblcost_gen_abast_placa').setGroupHeaders(
        {
            useColSpanStyle: true,
            groupHeaders: [
                { "numberOfColumns": 5, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>COSTO GENERAL POR ABASTECIMIENTO POR PLACA</h5></div><div class='col-md-4'><button class='btn btn-warning' @if($permiso[0]->btn_print == 1) onClick='traer_datos_desplegable(tblcost_gen_abast_placa,6);' @else onClick='sin_permiso();' @endif><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "ahorro",align: 'center'}]
        });

    });
    
    function mostrar_estaciones(parentRowID, parentRowKey) 
    {
        var childGridID = parentRowID + "_table";
        var childGridPagerID = parentRowID + "_pager";

        $('#' + parentRowID).append('<table id=' + childGridID + '></table><div id=' + childGridPagerID + ' class=scroll></div>');

        $("#" + childGridID).jqGrid({
            url: 'control_consumo/0?grid=estaciones_consumo&cca_id='+parentRowKey,
            mtype: "GET",
            datatype: "json",
            page: 1,
            colNames: ['ID', 'ESTACION','Q - ABASTECIDA'],
            cmTemplate: { sortable: false },
            sortname: 'cde_id', sortorder: 'asc', viewrecords: true,
            caption: '<div class="row"><div class="col-md-2">- ESTACION CONSUMO - </div><div class="col-md-9" id=cabecera_'+childGridID+'></div></div>', align: "center",
            colModel: [
                {name: 'cde_id', index: 'cde_id', align: 'left',width: 10, hidden:true},
                {name: 'est_descripcion', index: 'est_descripcion', align: 'left', width: 60},
                {name: 'cde_qabastecida', index: 'cde_qabastecida', align: 'center', width: 20}
            ],
            loadonce: true,
            width: 1300,
            height: '100%',
            loadComplete: function (data) {
                console.log(data);
                html = "";
                for(i=0;i < data.consumos.length; i++)
                {
                    html = html + ' <label>'+data.consumos[i].est_descripcion+'</label> /';
                    $("#cabecera_"+childGridID).html(html);
                }         
            },
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    modificar_consumo(Id);
                }
                else
                {
                    sin_permiso();
                }
            }
        });
    }
</script>
@stop

@endsection


