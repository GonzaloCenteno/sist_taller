@extends('principal.p_inicio')
@section('title', 'VIAJES')
@section('content')
<style>
    .ui-icon-minusthick {
        -ms-transform: scale(2);
        -webkit-transform: scale(2);
        transform: scale(2);
        margin-left: 10px;
        margin-top: 5px;
    }
    .ui-icon-plusthick {
        -ms-transform: scale(2);
        -webkit-transform: scale(2);
        transform: scale(2);
        margin-left: 10px;
        margin-top: 5px;
    }
</style>
<br>
<div class="card card-danger card-outline">
    <div class="card-header align-self-center">
        <h4 class="m-0"><i class="fa fa-clipboard fa-2x" aria-hidden="true"></i> REGISTRO DE VIAJES</h4>
    </div>
    <div class="card-body" id="bloque">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control form-control" style="width: 100%;" id="cbx_cantidad" name="cbx_cantidad">
                            <option value="0"> ..:: SELECCIONAR CANTIDAD DE MESES ::.. </option>
                            <option value="1"> ..:: 1 MES ::.. </option>
                            <option value="2"> ..:: 2 MES(ES) ::.. </option>
                            <option value="3"> ..:: 3 MES(ES) ::.. </option>
                            <option value="4"> ..:: 4 MES(ES) ::.. </option>
                            <option value="5"> ..:: 5 MES(ES) ::.. </option>
                            <option value="6"> ..:: 6 MES(ES) ::.. </option>
                            <option value="7"> ..:: 7 MES(ES) ::.. </option>
                            <option value="8"> ..:: 8 MES(ES) ::.. </option>
                            <option value="9"> ..:: 9 MES(ES) ::.. </option>
                            <option value="10"> ..:: 10 MES(ES) ::.. </option>
                            <option value="11"> ..:: 11 MES(ES) ::.. </option>
                            <option value="12"> ..:: 12 MES(ES) ::.. </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                @if( $permiso[0]->btn_new == 1 )
                    <button id="btn_generar_cronograma" type="button" class="btn btn-xl btn-outline-success btn-block" readonly="readonly"><i class="fa fa-plus-circle"></i> GENERAR CRONOGRAMA</button>
                @else
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-outline-success btn-block" readonly="readonly"><i class="fa fa-plus-circle"></i> GENERAR CRONOGRAMA</button>
                @endif
                </div>
                <div class="col-md-3 form-group input-group">
                    <input type="text" id="txt_placa" class="form-control text-center text-uppercase" maxlength="7" onClick="this.select()" placeholder="ESCRIBIR NUMERO DE PLACA" autocomplete="off">
                    <div class="input-group-prepend">
                        <button type="button" onclick="buscar_placa();" class="btn btn-outline-primary btn-block"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-3">
                    <button id="btn_limpiar_datos" type="button" class="btn btn-xl btn-outline-danger btn-block" readonly="readonly"><i class="fa fa-plus-circle"></i> LIMPIAR DATOS</button>
                </div>
            </div>
        </div>
        <br>
        <div class="form-group col-md-12">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblviajes"></table>
                <div id="paginador_tblviajes"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/programacion/viajes.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('submenu');
    
    jQuery(document).ready(function($){
        
        jQuery("#tblviajes").jqGrid({
            url: 'viajes/0?grid=viajes',
            datatype: 'json', mtype: 'GET',
            height: '465px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            colNames: ['ID', 'PLACAS','RUTAS','FECHA','HORA SALIDA','HORA LLEGADA','ESTADO','ESTILO'],
            rowNum: 248, sortname: 'pro_id', viewrecords: true, caption: '<button id="btn_act_tblviajes" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE VIAJES -', align: "center",
            colModel: [
                {name: 'via_id', index: 'via_id', align: 'left',width: 10, hidden:true},
                {name: 'veh_placa', index: 'veh_placa', align: 'center', width: 18, sortable: false, formatter: PlacaEstilo},
                {name: 'pro_rutas', index: 'pro_rutas', align: 'center', width: 15, sortable: false, formatter: RutaEstilo},
                {name: 'via_fecha', index: 'via_fecha', align: 'center', width: 15, formatter: 'date', formatoptions: {srcformat: 'Y-m-d', newformat: 'd/m/Y'}, sortable: false},
                {name: 'pro_horasalida', index: 'pro_horasalida', align: 'center', width: 15, sortable: false},
                {name: 'pro_horallegada', index: 'pro_horallegada', align: 'center', width: 15, sortable: false},
                {name: 'via_estado', index: 'via_estado', align: 'center', width: 10, sortable: false, formatter: EstadoEstilo},
                {name: 'pro_estilo', index: 'pro_estilo', align: 'center', width: 10, hidden:true}
            ],
            pager: '#paginador_tblviajes',
            rowList: [31, 62, 93, 124, 155, 186, 217, 248],
            grouping: true,
            loadComplete: function(data){
                if(data.total != 0){
                    for(var i = 0; i < data.rows.length; i++)
                    {
                        $($('#'+data.rows[i].id).children()[2]).css("background-color", data.rows[i].cell[7]);
                    }
                } 
            },
            groupingView: {
                groupField: ["via_fecha"],
                groupColumnShow: [true],
                groupText: ["<h4><b>&nbsp;&nbsp;{0} - {1} VIAJES</b></h4>"],
                groupOrder: ["asc"],
                groupSummary: [false],
                groupCollapse: true,
                minusicon: "ui-icon-minusthick",
                plusicon: "ui-icon-plusthick"
            },
            gridComplete: function () {
                var idarray = jQuery('#tblviajes').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                var firstid = jQuery('#tblviajes').jqGrid('getDataIDs')[0];
                    $("#tblviajes").setSelection(firstid);    
                }
            },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });
        
    });
</script>
@stop

@endsection


