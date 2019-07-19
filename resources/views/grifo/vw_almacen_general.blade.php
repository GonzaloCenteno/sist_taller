@extends('principal.p_inicio')
@section('title', 'CAPACIDAD') 
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
        <h4 class="m-0"><i class="fa fa-bar-chart fa-2x" aria-hidden="true"></i> LOGISTICA - ALMACEN GENERAL DE COMBUSTIBLE</h4>
    </div>
    <div class="card-body">
        <div class="card card-danger card-outline">
            <div class="card-header">
                <h5 class="m-0">DATOS GENERALES</h5>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                            <label>NUMERO:</label>
                            <div class="input-group">
                                <input type="text" id="txt_new_nrovale" name="txt_new_nrovale" class="form-control text-center text-uppercase" onClick="this.select()">
                                <div class="input-group-prepend">
                                    <button onclick="fn_buscar_nrovale();" class="btn btn-danger"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                            <label>FECHA EMISION:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" id="txt_cde_fecha" readonly="readonly" class="form-control text-center text-uppercase clsDatePicker otro">
                            </div>

                        </div>
                    </div>

                    <div class="col-md-6 col-xs-3">
                        <div class="form-group">
                            <label>REFERENCIA:</label>

                            <input type="hidden" id="hiddentxt_cde_copiloto" name="hiddentxt_cde_copiloto">
                            <input type="text" id="txt_cde_copiloto" class="form-control text-center text-uppercase" onClick="this.select()">

                        </div>
                    </div>
                    
                    <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                            <label>ALMACEN:</label>
                            <div class="input-group">
                                <input type="text" id="txt_new_nrovale" name="txt_new_nrovale" class="form-control text-center text-uppercase" onClick="this.select()">
                                <div class="input-group-prepend">
                                    <button onclick="fn_buscar_nrovale();" class="btn btn-danger"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xs-3">
                        <div class="form-group">
                            <label>DESCRIPCION ALMACEN:</label>

                            <input type="hidden" id="hiddentxt_cde_copiloto" name="hiddentxt_cde_copiloto">
                            <input type="text" id="txt_cde_copiloto" class="form-control text-center text-uppercase" onClick="this.select()">

                        </div>
                    </div>

                    <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                            <label>ESTADO:</label>

                            <input type="hidden" id="hiddentxt_cde_copiloto" name="hiddentxt_cde_copiloto">
                            <input type="text" id="txt_cde_copiloto" class="form-control text-center text-uppercase" onClick="this.select()">

                        </div>
                    </div>
                    
                    
                    <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                            <label>CODIGO TRABAJADOR:</label>
                            <div class="input-group">
                                <input type="text" id="txt_new_nrovale" name="txt_new_nrovale" class="form-control text-center text-uppercase" onClick="this.select()">
                                <div class="input-group-prepend">
                                    <button onclick="fn_buscar_nrovale();" class="btn btn-danger"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-9 col-xs-3">
                        <div class="form-group">
                            <label>NOMBRE TRABAJADOR:</label>
                            <div class="input-group">
                                <input type="text" id="txt_new_nrovale" name="txt_new_nrovale" class="form-control text-center text-uppercase" onClick="this.select()">
                                <div class="input-group-prepend">
                                    <button onclick="fn_buscar_nrovale();" class="btn btn-danger"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                            <label>PLACA:</label>
                            <div class="input-group">
                                <input type="text" id="txt_new_nrovale" name="txt_new_nrovale" class="form-control text-center text-uppercase" onClick="this.select()">
                                <div class="input-group-prepend">
                                    <button onclick="fn_buscar_nrovale();" class="btn btn-danger"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xs-3">
                        <div class="form-group">
                            <label>DESCRIPCION:</label>

                            <input type="hidden" id="hiddentxt_cde_copiloto" name="hiddentxt_cde_copiloto">
                            <input type="text" id="txt_cde_copiloto" class="form-control text-center text-uppercase" onClick="this.select()">

                        </div>
                    </div>

                    <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                            <label>CENTRO DE COSTO:</label>

                            <input type="hidden" id="hiddentxt_cde_copiloto" name="hiddentxt_cde_copiloto">
                            <input type="text" id="txt_cde_copiloto" class="form-control text-center text-uppercase" onClick="this.select()">

                        </div>
                    </div>

                    <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                            <label>CONTOMETRO INICIAL:</label>

                            <input type="hidden" id="hiddentxt_cde_copiloto" name="hiddentxt_cde_copiloto">
                            <input type="text" id="txt_cde_copiloto" class="form-control text-center text-uppercase" disabled="disabled">

                        </div>
                    </div>

                    <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                            <label>CONTOMETRO FINAL:</label>

                            <input type="hidden" id="hiddentxt_cde_copiloto" name="hiddentxt_cde_copiloto">
                            <input type="text" id="txt_cde_copiloto" class="form-control text-center text-uppercase" disabled="disabled">

                        </div>
                    </div>

                    <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                            <label>KILOMETRAJE ANTERIOR:</label>

                            <input type="hidden" id="hiddentxt_cde_copiloto" name="hiddentxt_cde_copiloto">
                            <input type="text" id="txt_cde_copiloto" class="form-control text-center text-uppercase" disabled="disabled">

                        </div>
                    </div>

                    <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                            <label>KILOMETRAJE:</label>

                            <input type="hidden" id="hiddentxt_cde_copiloto" name="hiddentxt_cde_copiloto">
                            <input type="text" id="txt_cde_copiloto" class="form-control text-center text-uppercase" onClick="this.select()">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!-- /.card -->

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/grifo/almacen_general.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('active');

//    jQuery(document).ready(function($){
//        jQuery("#tblcapacidad").jqGrid({
//            url: 'capacidad/0?grid=capacidad',
//            datatype: 'json', mtype: 'GET',
//            height: '450px', autowidth: true,
//            toolbarfilter: true,
//            sortable:false,
//            colNames: ['ID', 'VALOR','FECHA REGISTRO','ESTADO'],
//            rowNum: 10, sortname: 'cap_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_tblcapacidad" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - VALORES DE CAPACIDAD -', align: "center",
//            colModel: [
//                {name: 'cap_id', index: 'cap_id', align: 'left',width: 10, hidden:true},
//                {name: 'cap_val', index: 'cap_val', align: 'center', width: 40},
//                {name: 'cap_fecregistro', index: 'cap_fecregistro', align: 'center', width: 40},
//                {name: 'cap_estado', index: 'cap_estado', align: 'center', width: 20}
//            ],
//            pager: '#paginador_tblcapacidad',
//            rowList: [10, 20, 30, 40, 50],
//            gridComplete: function () {
//                    var idarray = jQuery('#tblcapacidad').jqGrid('getDataIDs');
//                    if (idarray.length > 0) {
//                    var firstid = jQuery('#tblcapacidad').jqGrid('getDataIDs')[0];
//                        $("#tblcapacidad").setSelection(firstid);    
//                    }
//                },
//            onSelectRow: function (Id){},
//            ondblClickRow: function (Id)
//            {
//                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
//                if(permiso == 1)
//                {
//                    $('#btn_modificar_capacidad').click();
//                }
//                else
//                {
//                    sin_permiso();
//                }
//            }
//        });
//
//        jQuery.fn.preventDoubleSubmission = function() {
//            cambiar_estado_ruta();
//        };

//    });
</script>
@stop

@endsection


