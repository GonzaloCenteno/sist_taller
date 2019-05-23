@extends('principal.p_inicio')
@section('title', 'COSTO ADBLUE')
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
                @if( $permiso[0]->btn_new == 1 )
                    <button id="btn_nuevo_costo_adblue" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus-square"></i> NUEVO COSTO</button>
                @else
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus-square"></i> NUEVO COSTO</button>
                @endif
                @if( $permiso[0]->btn_edit == 1 )
                    <button id="btn_modificar_costo_adblue" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR COSTO</button>
                @else
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR COSTO</button>
                @endif  
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
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('active');
    
    jQuery(document).ready(function($){
        var anio = new Date();
        $("#cbx_coa_anio").val(anio.getFullYear());

        jQuery("#tblcostos_adblue").jqGrid({
            url: 'costo_adblue/0?grid=costos_adblue',
            datatype: 'json', mtype: 'GET',
            height: '450px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            colNames: ['ID', 'AÑO','MES','COSTO','FECHA REGISTRO'],
            rowNum: 10, sortname: 'coa_mes', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblcostosadblue" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE COSTOS ADBLUE POR LITRO -', align: "center",
            colModel: [
                {name: 'coa_id', index: 'cap_id', align: 'left',width: 10, hidden:true},
                {name: 'coa_anio', index: 'coa_anio', align: 'center', width: 40},
                {name: 'coa_mes', index: 'coa_mes', align: 'center', width: 50},
                {name: 'coa_saldo', index: 'coa_saldo', align: 'center', width: 40},
                {name: 'coa_fecregistro', index: 'coa_fecregistro', align: 'center', width: 40}
            ],
            pager: '#paginador_tblcostos_adblue',
            rowList: [10, 20, 30, 40, 50],
            gridComplete: function () {
                    var idarray = jQuery('#tblcostos_adblue').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tblcostos_adblue').jqGrid('getDataIDs')[0];
                        $("#tblcostos_adblue").setSelection(firstid);    
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    $('#btn_modificar_costo_adblue').click();
                }
                else
                {
                    sin_permiso();
                }
            }
        });

    });
</script>
@stop

@endsection


