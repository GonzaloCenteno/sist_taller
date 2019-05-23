@extends('principal.p_inicio')
@section('title', 'CONSUMOS')
@section('content')
<style>
    .clsDatePicker {
    z-index: 100000 !important;
}
</style>
<br>
<div class="card card-danger card-outline">
    <div class="card-header">
        <h4 class="m-0">REGISTRO DE CONSUMOS</h4>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
        <div class="row text-center" id="cabecera_consumo">
            @if( $permiso[0]->btn_new == 1 )
                <div class="col-md-2" style="padding-top: 32px;">
                    <button id="btn_vw_nuevo_consumo_or" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-plus-square"></i> NUEVAS RUTAS</button>
                </div>
                <div class="col-md-2" style="padding-top: 32px;">
                    <button id="btn_vw_consumocab" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus"></i> NUEVOS CONSUMOS</button>
                </div>
            @else
                <div class="col-md-2" style="padding-top: 32px;">
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-plus-square"></i> NUEVAS RUTAS</button>
                </div>
                <div class="col-md-2" style="padding-top: 32px;">
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus"></i> NUEVOS CONSUMOS</button>
                </div>
            @endif  
            <div class="col-md-3">
                <div class="form-group">
                    <label>FECHA DESDE:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                        <input type="date" id="txt_buscar_fdesde" class="form-control text-center text-uppercase">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>FECHA HASTA:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                        <input type="date" id="txt_buscar_fhasta" class="form-control text-center text-uppercase">
                    </div>
                </div>
            </div>
            <div class="col-lg-2" style="padding-top: 32px;">
                <button id="btn_vw_buscar_consumos" type="button" class="btn btn-xl btn-success" readonly="readonly"><i class="fa fa-search"></i> BUSCAR</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="col-lg-2">
                <div class="form-group">
                    <label>SELECCIONAR CAPACIDAD:</label>

                    <select class="form-control" style="width: 100%;" id="cbx_capacidad" name="cbx_capacidad">
                        @foreach($capacidad as $cap)
                        <option value="{{ $cap->cap_val }}"> {{ $cap->cap_val }} </option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-lg-10">
                <div id="listadoButtons">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ESCRIBIR N° VALE:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                    </div>
                                    <input type="text" id="txt_buscar_nrovale" class="form-control text-center text-uppercase">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ESCRIBIR N° PLACA:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-th-list"></i></span>
                                    </div>
                                    <input type="text" id="txt_buscar_placa" class="form-control text-center text-uppercase">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ESCRIBIR RUTA:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                    </div>
                                    <input type="text" id="txt_buscar_ruta" class="form-control text-center text-uppercase">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ESCRIBIR ESTACION:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                    </div>
                                    <input type="text" id="txt_buscar_estacion" class="form-control text-center text-uppercase">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="formularioButtons" style="display:none; padding-top: 32px;">
                    <button id="btn_vw_consumoscab_Guardar" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-save"></i> GUARDAR REGISTROS</button>
                    <button id="btn_vw_consumoscab_Cancelar" type="button" class="btn btn-xl btn-default" readonly="readonly"><i class="fa fa-arrow-circle-left"></i> REGRESAR</button>
                </div> 
            </div>
        </div>

        <div class="col-xs-12 center-block" id="formularioRegistros" style="display: none;">
            <form id="FormularioConsumoDetalle" name="FormularioConsumoDetalle" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SELECCIONAR UNA RUTA:</label>

                            <select class="form-control select2" style="width: 100%;" id="cbx_consumo_ruta" name="cbx_consumo_ruta">
                            </select>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>SELECCIONAR PLACA:</label>

                            <select class="form-control select2" style="width: 100%;" id="cbx_placa" name="cbx_placa">
                                @foreach($placas as $veh)
                                <option value="{{ $veh->veh_id }}"> {{ $veh->veh_placa }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" style="padding-top: 25px;">
                        <div class="form-group">
                            <button id="btn_generar_consumodet" type="button" class="btn btn-xl btn-danger btn-block" readonly="readonly"><i class="fa fa-plus-square"></i> GENERAR</button>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <table id="consumodet" class="table table-striped table-bordered table-condensed table-hover">
                            <thead style="background-color:#A9D0F5">
                            <th style="width: 10%;">FECHA</th>
                            <th style="width: 5%;">ESTACION</th>
                            <th style="width: 15%;">CONDUCTOR</th>
                            <th style="width: 15%;">PILOTO</th>
                            <th style="width: 10%;">KM</th>
                            <th style="width: 10%;">%STOP EN TANQUE</th>
                            <th style="width: 10%;">Q-ABAST.</th>
                            <th style="width: 15%;">OBSERVACIONES</th>
                            <th style="width: 8%;">INGRESO</th>
                            <th style="width: 10%;">SALIDA</th>
                            <th style="width: 10%;">STOP</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </form>
            <div style="padding-left: 10px">
                <button id="btn_vw_otrosconsumos_Guardar" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-save"></i> GUARDAR REGISTROS</button>
            </div>
        </div>

        <div class="form-group col-md-12" id="listadoRegistros">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblconsumosdet"></table>
                <div id="paginador_tblconsumosdet"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

<div class="modal fade" id="ModalConsumos">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">

                            <div class="input-group">
                                <h4 id="lbl_cde_vale"> </h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">

                            <div class="input-group">
                                <h4 id="lbl_cde_placa"></h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">

                            <div class="input-group">
                                <h4 id="lbl_cde_estacion"></h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">

                            <div class="input-group">
                                <h4 id="lbl_cde_ruta"></h4>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="m-0">RESUMEN</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>FECHA:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_fecha" readonly="readonly" class="form-control text-center text-uppercase clsDatePicker otro">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CONDUCTOR:</label>

                                    <select class="form-control select2" style="width: 100%;" id="txt_cde_conductor" name="txt_cde_conductor">
                                        @foreach($tripulantes as $tri)
                                        <option value="{{ $tri->tri_id }}"> {{ $tri->tri_nombre }} {{ $tri->tri_apaterno }} {{ $tri->tri_amaterno }} </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>COPILOTO:</label>

                                    <select class="form-control select2" style="width: 100%;" id="txt_cde_copiloto" name="txt_cde_copiloto">
                                        @foreach($tripulantes as $tri)
                                        <option value="{{ $tri->tri_id }}"> {{ $tri->tri_nombre }} {{ $tri->tri_apaterno }} {{ $tri->tri_amaterno }} </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="m-0">COMSUMOS</h5>
                            </div>
                            <div class="col-md-6 text-center" id="cabeceraModalConsumo">
                                <button type="button" id="btn_modificar_qparcial" class="btn btn-danger btn-xl"><i class="fa fa-pencil-square-o"></i> MODIFICAR Q-ABAST.</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>KILOMETROS:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_km" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="6" placeholder="0">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>% STOP EN TANQUE:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-percent"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_xtanque" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Q - ABAST:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_qabast" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>OBSERVACIONES:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-th-list"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_observaciones" class="form-control text-center text-uppercase" maxlength="255">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="m-0">RESERVA</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>INGRESO:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-gear"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_ingreso" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>SALIDA:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-gear"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_salida" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>STOP:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-gear"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_stop" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000">
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="btn_actualizar_consumo" class="btn btn-warning btn-xl"></button>
                <button type="button" id="btn_cerrar_modal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalNuevaRuta">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body ui-front">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="m-0">DATOS</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>VALE:</label>

                                    <div class="input-group">
                                        <input type="hidden" id="txt_new_cca_id" name="txt_new_cca_id" class="modal_new">
                                        <input type="hidden" id="txt_new_veh_id" name="txt_new_veh_placa" class="modal_new">
                                        <input type="hidden" id="txt_new_rut_id" name="txt_new_rut_id" class="modal_new">
                                        <input type="text" id="txt_new_nrovale" name="txt_new_nrovale" class="form-control text-center text-uppercase">
                                        <div class="input-group-prepend">
                                            <button onclick="fn_buscar_nrovale();" class="btn btn-success"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ESTACION:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="hidden" id="hiddentxt_new_estacion" name="hidden_txt_new_estacion" class="modal_new">
                                        <input type="text" id="txt_new_estacion" class="form-control text-center text-uppercase modal_new">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="m-0">RESUMEN</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>FECHA:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" id="txt_new_fecha" name="txt_new_fecha" class="form-control text-center text-uppercase modal_new clsDatePicker otro" readonly="readonly">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CONDUCTOR:</label>

                                    <input type="hidden" id="hiddentxt_new_conductor" name="hidden_txt_new_conductor" class="modal_new">
                                    <input type="text" id="txt_new_conductor" class="form-control text-center text-uppercase modal_new">

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>COPILOTO:</label>

                                    <input type="hidden" id="hiddentxt_new_copiloto" name="hidden_txt_new_copiloto" class="modal_new">
                                    <input type="text" id="txt_new_copiloto" class="form-control text-center text-uppercase modal_new">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="m-0">COMSUMOS</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>KILOMETROS:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="text" id="txt_new_kilometros" name="txt_new_kilometros" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="6" placeholder="0">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>% STOP EN TANQUE:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-percent"></i></span>
                                        </div>
                                        <input type="text" id="txt_new_xtanque" name="txt_new_xtanque" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Q - ABAST:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="text" id="txt_new_qabast" name="txt_new_qabast" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>OBSERVACIONES:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-th-list"></i></span>
                                        </div>
                                        <input type="text" id="txt_new_observaciones" name="txt_new_observaciones" class="form-control text-center text-uppercase modal_new" maxlength="255">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="m-0">RESERVA</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>INGRESO:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-gear"></i></span>
                                        </div>
                                        <input type="text" id="txt_new_ingreso" name="txt_new_ingreso" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>SALIDA:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-gear"></i></span>
                                        </div>
                                        <input type="text" id="txt_new_salida" name="txt_new_salida" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>STOP:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-gear"></i></span>
                                        </div>
                                        <input type="text" id="txt_cde_stop" name="txt_cde_stop" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000">
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_crear_nueva_ruta" class="btn btn-success btn-xl modal_new"></button>
                <button type="button" id="btn_cerrar_modal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
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
                    <div class="col-md-3">
                        <div class="form-group">

                            <div class="input-group">
                                <h4 id="lbl_conpar_vale"> </h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">

                            <div class="input-group">
                                <h4 id="lbl_conpar_placa"></h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">

                            <div class="input-group">
                                <h4 id="lbl_conpar_estacion"></h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">

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
                    <div class="card-body" id="principal" style="display:none;">
                        <div class="col-md-12 text-center">
                            <div class="form-group">
                                <button type="button" id="btn_generar_cqparciales" class="btn btn-success btn-xl"><i class="fa fa-plus-square"> AGREGAR</i></button>
                            </div>
                        </div>
                        
                        <form id="FormularioQabastParcial" name="FormularioQabastParcial" method="post" enctype="multipart/form-data">
                        @csrf
                            <div class="row" id="detalle_qabast_parcial_1">
                            </div>
                        </form>
                    </div>
                    <div class="card-body" id="secundario" style="display:none;">
                        <form id="FormularioQabastParcialMod" name="FormularioQabastParcialMod" method="post" enctype="multipart/form-data">
                        @csrf
                            <div class="row" id="detalle_qabast_parcial_2">
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="btn_agregar_cqparciales" class="btn btn-warning btn-xl" style="display:none;"></button>
                <button type="button" id="btn_actualizar_cqparciales" class="btn btn-success btn-xl" style="display:none;"><i class="fa fa-pencil-square-o"></i> ACTUALIZAR Q-ABAST.</button>
                <button type="button" id="btn_cerrar_modal_1" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/consumos.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('active');
    jQuery(document).ready(function($){
        
        mostrarformulario(false);

        jQuery("#tblconsumosdet").jqGrid({
            url: 'consumo/0?grid=consumos&indice=0',
            datatype: 'json', mtype: 'GET',
            height: '370px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            shrinkToFit: false,
            forceFit:true,  
            scroll: false,
            colNames: ['ID', 'Q-PARCIAL','FECHA','IDCAB','VALE','PLACA','RUTA','ESTACION','CONDUCTOR','COPILOTO','KM','%','Q-LT','%','Q-LT','Q-ABAST','OBSERVACIONES','INGRESO','SALIDA','STOP'],
            rowNum: 20, sortname: 'cde_id', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblconsumos" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE CONSUMOS -', align: "center",
            colModel: [
                {name: 'cde_id', index: 'cde_id', align: 'left',width: 10,hidden:true,frozen:true},
                {name: 'cde_qparcial', index: 'cde_qparcial', align: 'center', width: 70,frozen:true,formatter: OptionFormato},
                {name: 'cde_fecha', index: 'cde_fecha', align: 'center', width: 90,frozen:true},
                {name: 'cca_id', index: 'cca_id', align: 'center', width: 70,hidden:true,frozen:true},
                {name: 'nro_vale', index: 'nro_vale', align: 'center', width: 55,frozen:true},
                {name: 'veh_placa', index: 'veh_placa', align: 'center', width: 80,frozen:true},
                {name: 'rut_descripcion', index: 'rut_descripcion', align: 'center', width: 70},
                {name: 'est_descripcion', index: 'est_descripcion', align: 'left', width: 110},
                {name: 'conductor', index: 'conductor', align: 'left', width: 350},
                {name: 'copiloto', index: 'copiloto', align: 'left', width: 350},
                {name: 'cde_kilometros', index: 'cde_kilometros', align: 'center', width: 70},
                {name: 'cde_xtanque', index: 'cde_xtanque', align: 'center', width: 60,formatter: 'integer',formatoptions: { suffix: '%' }},
                {name: 'cde_qlttanque', index: 'cde_qlttanque', align: 'center', width: 70},
                {name: 'cde_xconsumida', index: 'cde_xconsumida', align: 'center', width: 70,formatter: 'integer',formatoptions: { suffix: '%' }},
                {name: 'cde_qltconsumida', index: 'cde_qltconsumida', align: 'center', width: 70},
                {name: 'cde_qabastecida', index: 'cde_qabastecida', align: 'center', width: 70},
                {name: 'cde_observaciones', index: 'cde_observaciones', align: 'left', width: 350},
                {name: 'cde_ingreso', index: 'cde_ingreso', align: 'center', width: 70},
                {name: 'cde_salida', index: 'cde_salida', align: 'center', width: 70},
                {name: 'cde_stop', index: 'cde_stop', align: 'center', width: 70},
            ],
            pager: '#paginador_tblconsumosdet',
            rowList: [10, 20, 30, 40, 50],
            gridComplete: function () {
                    var idarray = jQuery('#tblconsumosdet').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tblconsumosdet').jqGrid('getDataIDs')[0];
                        $("#tblconsumosdet").setSelection(firstid);    
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    modificar_consumodetalle(Id);
                }
                else
                {
                    sin_permiso();
                }
            }
        });

        $('#tblconsumosdet').setGroupHeaders(
        {
            useColSpanStyle: true,
            groupHeaders: [
                { "numberOfColumns": 5, "titleText": "<center><h5>RESUMEN</h5></center>", "startColumnName": "rut_descripcion",align: 'center'},
                { "numberOfColumns": 2, "titleText": "<center><h5>STOP-TANQ.</h5></center>", "startColumnName": "cde_xtanque",align: 'center' },
                { "numberOfColumns": 2, "titleText": "<center><h5>Q-CONS.</h5></center>", "startColumnName": "cde_xconsumida",align: 'center' },
                { "numberOfColumns": 2, "titleText": "<center><h5>ABASTECIMIENTO</h5></center>", "startColumnName": "cde_qabastecida",align: 'center' },
                { "numberOfColumns": 3, "titleText": "<center><h5>RESERVA</h5></center>", "startColumnName": "cde_ingreso",align: 'center' }]
        });

        $("#tblconsumosdet").jqGrid("destroyFrozenColumns")
                .jqGrid("setColProp", "cde_qparcial", { frozen: true })
                .jqGrid("setColProp", "cde_fecha", { frozen: true })
                .jqGrid("setColProp", "nro_vale", { frozen: true })
                .jqGrid("setColProp", "veh_placa", { frozen: true })
                .jqGrid("setFrozenColumns")
                .trigger("reloadGrid", [{ current: true}]);

        $(".select2").select2();

        $(document).on("focus", ".otro", function(){
            $(this).datepicker({ minDate: -5,dateFormat: 'dd-mm-yy',showAnim: 'clip' });

            $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '<<',
            nextText: '>>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);
        });

    });
    
    function OptionFormato(cellValue, options, rowObject) {
        permiso = {!! json_encode($permiso[0]->btn_new) !!};
        if(permiso == 1)
        {
            var opciones = (parseInt(cellValue) == 0) ? '<button onclick="consumo_parcial('+rowObject[0]+','+rowObject[1]+')" type="button" class="btn btn-xl btn-success"><i class="fa fa-gear"></i> </button>' : '<button onclick="consumo_parcial('+rowObject[0]+','+rowObject[1]+')" type="button" class="btn btn-xl btn-danger"><i class="fa fa-gears"></i> </button>';
        }
        else
        {
            var opciones = (parseInt(cellValue) == 0) ? '<button onclick="sin_permiso()" type="button" class="btn btn-xl btn-success"><i class="fa fa-gear"></i> </button>' : '<button onclick="sin_permiso()" type="button" class="btn btn-xl btn-danger"><i class="fa fa-gears"></i> </button>';
        }
        return opciones;
    }
</script>
@stop

@endsection


