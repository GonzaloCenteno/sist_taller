@extends('principal.p_inicio')
@section('title', 'ESTACIONES')
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
        <h4 class="m-0"><i class="fa fa-map-marker fa-2x" aria-hidden="true"></i> REGISTRO DE ESTACIONES</h4>
    </div>
    <div class="card-body">
        <div class="col-lg-12">
            <center>
                @if( $permiso[0]->btn_new == 1 )
                    <button id="btn_nueva_estacion" type="button" class="btn btn-xl btn-outline-danger" readonly="readonly"><i class="fa fa-plus-square"></i> CREAR ESTACION</button>
                @else
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-outline-danger" readonly="readonly"><i class="fa fa-plus-square"></i> CREAR ESTACION</button>
                @endif
                @if( $permiso[0]->btn_edit == 1 )
                    <button id="btn_modificar_estacion" type="button" class="btn btn-xl btn-outline-warning" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR ESTACION</button>
                @else
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-outline-warning" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR ESTACION</button>
                @endif
            </center>
        </div>
        <br>
        <div class="form-group col-md-12">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblestaciones"></table>
                <div id="paginador_tblestaciones"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

<div class="modal fade" id="ModalEstaciones">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>DESCRIPCION:</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                        </div>
                        <input type="text" id="txt_est_descripcion" class="form-control text-center text-uppercase" placeholder="ESCRIBIR DESCRIPCION DE LA ESTACION" maxlength="100">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_crear_estacion" class="btn btn-primary btn-xl"></button>
                <button type="button" id="btn_actualizar_estacion" class="btn btn-warning btn-xl"></button>
                <button type="button" id="btn_cerrar_modal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/estaciones.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('submenu');
    
    jQuery(document).ready(function($){
        jQuery("#tblestaciones").jqGrid({
            url: 'estacion/0?grid=estaciones',
            datatype: 'json', mtype: 'GET',
            height: '480px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            colNames: ['ID', 'DESCRIPCION','FECHA REGISTRO','ESTADO'],
            rowNum: 10, sortname: 'est_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_tblestacion" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE ESTACIONES -', align: "center",
            colModel: [
                {name: 'est_id', index: 'est_id', align: 'left',width: 10, hidden:true},
                {name: 'est_descripcion', index: 'est_descripcion', align: 'left', width: 60},
                {name: 'est_fecregistro', index: 'est_fecregistro', align: 'center', width: 15},
                {name: 'est_estado', index: 'est_estado', align: 'center', width: 10}
            ],
            pager: '#paginador_tblestaciones',
            rowList: [10, 20, 30, 40, 50],
            gridComplete: function () {
                    var idarray = jQuery('#tblestaciones').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tblestaciones').jqGrid('getDataIDs')[0];
                        $("#tblestaciones").setSelection(firstid);    
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    $('#btn_modificar_estacion').click();
                }
                else
                {
                    sin_permiso();
                }
            }
        });

        jQuery.fn.preventDoubleSubmission = function() {
            cambiar_estado_estacion();
        };

    });
</script>
@stop

@endsection


