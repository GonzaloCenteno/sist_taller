@extends('principal.p_inicio')
@section('title', 'VALES') 
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
        <h4 class="m-0"><i class="fa fa-list-alt fa-2x" aria-hidden="true"></i> REGISTRO DE VALES - GRIFO</h4>
    </div>
    <div class="card-body" id="contenedors">
        <div class="row">
            <div class="col-md-1 col-xs-3">
                <div class="form-group">
                    <label>VALE:</label>
                    <input type="text" id="txt_buscar_vale" class="form-control text-center text-uppercase" autocomplete="off" maxlength="7">
                </div>
            </div>
            <div class="col-md-3 col-xs-3">
                <div class="form-group">
                    <label>TRIPULANTE:</label>
                    <input type="text" id="txt_buscar_tripulante" class="form-control text-center text-uppercase" autocomplete="off">
                </div>
            </div>
            <div class="col-md-2 col-xs-3">
                <div class="form-group">
                    <label>PLACA:</label>
                    <input type="text" id="txt_buscar_placa" class="form-control text-center text-uppercase" autocomplete="off">
                </div>
            </div>
            <div class="col-md-2 col-xs-3">
                <div class="form-group">
                    <label>FECHA INICIO:</label>
                    <input type="date" id="txt_fec_inicio" class="form-control text-center text-uppercase" autocomplete="off">
                </div>
            </div>
            <div class="col-md-2 col-xs-3">
                <div class="form-group">
                    <label>FECHA FIN:</label>
                    <input type="date" id="txt_fec_fin" class="form-control text-center text-uppercase" autocomplete="off">
                </div>
            </div>
            <div class="col-md-2 col-xs-3 mt-4">
                <div class="form-group">
                    <button type="button" id="btn_bus_vales_grifo" class="btn btn-outline-danger btn-block"><i class="fa fa-search"></i> BUSCAR</button>
                </div>
            </div>
        </div>
        <br>
        <div class="form-group col-md-12" id="tabla_vales">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblvalesgrifo"></table>
                <div id="paginador_tblvalesgrifo"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/grifo/grifo_vales.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('submenu');
        
    jQuery(document).ready(function($){
        jQuery("#tblvalesgrifo").jqGrid({
            url: 'vales_grifo/0?grid=vales&indice=0',
            datatype: 'json', mtype: 'GET',
            height: '400px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            shrinkToFit: false,
            forceFit:true,  
            scroll: false,
            colNames: ['ID', 'ESTADO','VALE','PLACA','DNI','CONDUCTOR','FECHA','HORA','ESTACION','KILOMETRAJE','CONTOMETRO INICIAL','CONTOMETRO FINAL','BOMBA','CENTRO DE COSTO','CODIGO PRODUCTO','PRODUCTO','UNIDAD','CANTIDAD'],
            rowNum: 20, sortname: 'vca_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_tblvalesgrifo" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE VALES - GRIFO -', align: "center",
            colModel: [
                {name: 'vca_id', index: 'vca_id', align: 'left',width: 10, hidden:true,frozen:true},
                {name: 'vca_estado', index: 'vca_estado', align: 'center', width: 60,frozen:true,formatter: EstadoEstilo,sortable: false},
                {name: 'vca_numvale', index: 'vca_numvale', align: 'center', width: 80,frozen:true,sortable: false},
                {name: 'veh_placa', index: 'veh_placa', align: 'center', width: 90,frozen:true,sortable: false},
                {name: 'tri_nrodoc', index: 'tri_nrodoc', align: 'center', width: 80},
                {name: 'tripulante', index: 'tripulante', align: 'left', width: 360},
                {name: 'vca_fecha', index: 'vca_fecha', align: 'center', width: 90},
                {name: 'vca_hora', index: 'vca_hora', align: 'center', width: 70},
                {name: 'est_descripcion', index: 'est_descripcion', align: 'left', width: 100},
                {name: 'vca_kilometraje', index: 'vca_kilometraje', align: 'center', width: 100},
                {name: 'vca_cntmtri', index: 'vca_cntmtri', align: 'center', width: 150},
                {name: 'vca_cntmtrf', index: 'vca_cntmtrf', align: 'center', width: 150},
                {name: 'bom_descripcion', index: 'bom_descripcion', align: 'left', width: 110},
                {name: 'cec_codigo', index: 'cec_codigo', align: 'center', width: 130},
                {name: 'pro_codigo', index: 'pro_codigo', align: 'center', width: 130},
                {name: 'pro_descripcion', index: 'pro_descripcion', align: 'left', width: 170},
                {name: 'pro_unidad', index: 'pro_unidad', align: 'left', width: 70},
                {name: 'vde_cantidad', index: 'vde_cantidad', align: 'center', width: 80}
            ],
            pager: '#paginador_tblvalesgrifo',
            rowList: [20, 40, 60, 80, 100],
            gridComplete: function () {
                var idarray = jQuery('#tblvalesgrifo').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#tblvalesgrifo').jqGrid('getDataIDs')[0];
                    $("#tblvalesgrifo").setSelection(firstid);    
                }
            }
        });
        
        $('#tblvalesgrifo').setGroupHeaders(
        {
            useColSpanStyle: true,
            groupHeaders: [
                { "numberOfColumns": 2, "titleText": "<center><h5>TRIPULANTE</h5></center>", "startColumnName": "tri_nrodoc",align: 'center'},
                { "numberOfColumns": 12, "titleText": "<center><h5>INFORMACION VALE</h5></center>", "startColumnName": "vca_fecha",align: 'center' }]
        });
        
        $("#tblvalesgrifo").jqGrid("destroyFrozenColumns")
                .jqGrid("setColProp", "vca_estado", { frozen: true })
                .jqGrid("setColProp", "vca_numvale", { frozen: true })
                .jqGrid("setColProp", "veh_placa", { frozen: true })
                .jqGrid("setFrozenColumns")
                .trigger("reloadGrid", [{ current: true}]);
            
        function EstadoEstilo(cellValue, options, rowObject) {
            permiso = {!! json_encode($permiso[0]->btn_del) !!};
            if(permiso == 1)
            {
                var opciones = (parseInt(cellValue) == 0) ? '<button type="button" class="btn btn-xl btn-danger btn-round-animate"><i class="fa fa-times"></i> </button>' : '<button id="btn_anular_vale_grifo" value="'+rowObject[0]+'" data-vale="'+rowObject[2]+'" type="button" class="btn btn-xl btn-success btn-round-animate"><i class="fa fa-check"></i> </button>';
            }
            else
            {
                var opciones = (parseInt(cellValue) == 0) ? '<button onclick="sin_permiso()" type="button" class="btn btn-xl btn-danger btn-round-animate"><i class="fa fa-times"></i> </button>' : '<button onclick="sin_permiso()" type="button" class="btn btn-xl btn-success btn-round-animate"><i class="fa fa-check"></i> </button>';
            }
            return opciones;
        }
    });
</script>
@stop

@endsection


