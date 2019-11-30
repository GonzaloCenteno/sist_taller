@extends('principal.p_inicio')
@section('title', 'RUTA ESTACION')
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
        <h4 class="m-0"><i class="fa fa-arrows-alt fa-2x" aria-hidden="true"></i> REGISTRO DE RUTA - ESTACIONES</h4>
    </div>
    <div class="card-body">
        <div class="col-lg-12">
            <center>
                @if( $permiso[0]->btn_new == 1 )
                    <div id="listadoButtons">
                        <button id="btn_vw_rtestacion_Nuevo" type="button" class="btn btn-xl btn-outline-danger" readonly="readonly"><i class="fa fa-plus"></i> NUEVOS REGISTROS</button>
                    </div>
                @else
                    <div id="listadoButtons">
                        <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-outline-danger" readonly="readonly"><i class="fa fa-plus"></i> NUEVOS REGISTROS</button>
                    </div>
                @endif
                
                <div id="formularioButtons" style="display:none;">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">

                                <div class="input-group">
                                    <h4 id_ruta="" id="lbl_rte_ruta_create"></h4>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <button id="btn_generar_estaciones" type="button" class="btn btn-xl btn-outline-danger" readonly="readonly"><i class="fa fa-plus-square"> GENERAR ESTACIONES</i></button>
                            <button id="btn_vw_rtestacion_Cancelar" type="button" class="btn btn-xl btn-outline-primary" readonly="readonly"><i class="fa fa-arrow-circle-left"></i> REGRESAR</button>
                        </div>
                    </div>
                </div> 
            </center>
        </div>

        <div class="col-xs-12 center-block" id="formularioRegistros" style="display: none;">
            <form id="FormularioRtesaciones" name="FormularioRtesaciones" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <table class="table table-striped table-bordered table-condensed table-hover">
                            <thead style="background-color:#DC3546; color: #ffffff">
                            <th style="width: 10%;">BORRAR</th>
                            <th style="width: 70%;">ESTACION</th>
                            <th style="width: 20%;">CONSUMO</th>
                            </thead>
                            <tbody id="cuerpo_rtestaciones">

                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <div style="padding-left: 10px">
                <button id="btn_vw_rtestacion_Guardar" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-save"></i> GUARDAR REGISTROS</button>
            </div>
        </div>

        <br>
        <div class="form-group col-md-12" id="listadoRegistros">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblrutaestacion"></table>
                <div id="paginador_tblrutaestacion"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

<div class="modal fade" id="ModalRutaEstacion">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">

                            <div class="input-group">
                                <h4 rte_id="" id="lbl_rte_ruta"> </h4>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ESTACION:</label>

                                    <select class="form-control select2" style="width: 100%;" id="txt_rte_estacion">
                                        @foreach($estaciones as $est)
                                        <option value="{{ $est->est_id }}"> {{ $est->est_descripcion }} </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>CONSUMO:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="text" id="txt_rte_consumo" class="form-control text-center text-uppercase" onkeypress="return soloNumeroTab(event);">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="btn_actualizar_rtestacion" class="btn btn-warning btn-xl"></button>
                <button type="button" id="btn_cerrar_modal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/ruta_estacion.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('submenu');
        
    jQuery(document).ready(function($){
        
        mostrarformulario(false);

        jQuery("#tblrutaestacion").jqGrid({
            url: 'ruta_estacion/0?grid=rutas',
            datatype: 'json', mtype: 'GET',
            height: '480px', autowidth: true,
            colNames: ['ID', 'DESCRIPCION','FECHA REGISTRO'],
            rowNum: 30, sortname: 'rut_id', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblruta" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE RUTAS -', align: "center",
            colModel: [
                {name: 'rut_id', index: 'rut_id', align: 'left',width: 10, hidden:true},
                {name: 'rut_descripcion', index: 'rut_descripcion', align: 'left', width: 20},
                {name: 'rut_fecregistro', index: 'rut_fecregistro', align: 'center', width: 15}
            ],
            pager: '#paginador_tblrutaestacion',
            rowList: [10, 20, 30, 40, 50],
            subGrid: true,
            subGridRowExpanded: showChildGrid,
            subGridOptions : {
                reloadOnExpand :false,
                selectOnExpand : true 
            },
            gridComplete: function () {
                var idarray = jQuery('#tblrutaestacion').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                var firstid = jQuery('#tblrutaestacion').jqGrid('getDataIDs')[0];
                    $("#tblrutaestacion").setSelection(firstid);    
                }
            },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });

        jQuery.fn.preventDoubleSubmission = function() {
            cambiar_estado_estacion();
        };

        $(".select2").select2();
    });
    
    
    jQuery(document).on("click", "#menu_push", function(){    
        if ($("#body_push").hasClass('sidebar-mini sidebar-collapse')) 
        {
            setTimeout(function (){
                var width = $('#contenedor').width();
                $('#tblrutaestacion').setGridWidth(width);
            }, 300);
        }
        else
        {
           setTimeout(function (){
                var width = $('#contenedor').width();
                $('#tblrutaestacion').setGridWidth(width);
           }, 300);
        } 
    });

    function showChildGrid(parentRowID, parentRowKey) {
        var childGridID = parentRowID + "_table";
        var childGridPagerID = parentRowID + "_pager";

        $('#' + parentRowID).append('<table id=' + childGridID + '></table><div id=' + childGridPagerID + ' class=scroll></div>');

        $("#" + childGridID).jqGrid({
            url: 'ruta_estacion/0?grid=ruta_estaciones&rut_id='+parentRowKey,
            mtype: "GET",
            datatype: "json",
            page: 1,
            colNames: ['ID', 'ESTACION','CONSUMO','FECHA REGISTRO','AÑO','ESTADO'],
            colModel: [
                {name: 'rte_id', index: 'rte_id', align: 'left',width: 10, hidden:true},
                {name: 'est_descripcion', index: 'est_descripcion', align: 'left', width: 60},
                {name: 'rte_consumo', index: 'rte_consumo', align: 'center', width: 15},
                {name: 'rte_fecregistro', index: 'rte_fecregistro', align: 'center', width: 12},
                {name: 'rte_anio', index: 'rte_anio', align: 'center', width: 10},
                {name: 'rte_estado', index: 'rte_estado', align: 'center', width: 10}
            ],
            loadonce: true,
            autowidth: true,
            height: '100%',
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    modificar_rtestacion(Id);
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


