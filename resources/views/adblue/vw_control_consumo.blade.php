@extends('principal.p_inicio')

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
                    <div class="col-lg-3" style="padding-top: 32px;">
                        <button id="btn_vw_nuevos_condet" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-plus-square"></i> AGREGAR / MODIFICAR DETALLE</button>
                    </div>
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

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/control_consumo.js') }}"></script>
@stop

@endsection


