@extends('principal.p_inicio')

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
        <div class="col-lg-6">
            <button id="btn_vw_nuevo_consumo_or" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-plus-square"></i> NUEVAS RUTAS</button>
            <button id="btn_vw_consumocab" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus"></i> NUEVOS CONSUMOS</button>
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
                        <div class="col-lg-2">
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
                                        <input type="text" id="txt_cde_km" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="6">
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
                                        <input type="text" id="txt_cde_xtanque" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="8">
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
                                        <input type="text" id="txt_cde_qabast" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="8">
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
                                        <input type="text" id="txt_cde_ingreso" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="8">
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
                                        <input type="text" id="txt_cde_salida" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="8">
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
                                        <input type="text" id="txt_cde_stop" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);" maxlength="8">
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
                                        <input type="text" id="txt_new_kilometros" name="txt_new_kilometros" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="6">
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
                                        <input type="text" id="txt_new_xtanque" name="txt_new_xtanque" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="8">
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
                                        <input type="text" id="txt_new_qabast" name="txt_new_qabast" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="8">
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
                                        <input type="text" id="txt_new_ingreso" name="txt_new_ingreso" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="8">
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
                                        <input type="text" id="txt_new_salida" name="txt_new_salida" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="8">
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
                                        <input type="text" id="txt_cde_stop" name="txt_cde_stop" class="form-control text-center text-uppercase modal_new" onkeypress="return soloNumeroTab(event);" maxlength="8">
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

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/consumos.js') }}"></script>
@stop

@endsection


