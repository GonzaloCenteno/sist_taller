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
        <h4 class="m-0"><i class="fa fa-list-ul fa-2x" aria-hidden="true"></i> MANTENIMIENTO CAPACIDAD</h4>
    </div>
    <div class="card-body" id="contenedors">
        <div class="col-lg-12">
            <center>
                @if( $permiso[0]->btn_new == 1 )
                    <button id="btn_nueva_capacidad" type="button" class="btn btn-xl btn-outline-danger" readonly="readonly"><i class="fa fa-plus-square"></i> CREAR CAPACIDAD</button>
                @else
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-outline-danger" readonly="readonly"><i class="fa fa-plus-square"></i> CREAR CAPACIDAD</button>
                @endif
                @if( $permiso[0]->btn_edit == 1 )
                    <button id="btn_modificar_capacidad" type="button" class="btn btn-xl btn-outline-warning" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR CAPACIDAD</button>
                @else
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-outline-warning" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR CAPACIDAD</button>
                @endif
            </center>
        </div>
        <br>
        <div class="form-group col-md-12">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblcapacidad"></table>
                <div id="paginador_tblcapacidad"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

<div class="modal fade" id="ModalCapacidad">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>VALOR:</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                        </div>
                        <input type="text" id="txt_cap_valor" class="form-control text-center" placeholder="ESCRIBIR VALOR" onkeypress="return soloNumeroTab(event);">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_crear_capacidad" class="btn btn-primary btn-xl"></button>
                <button type="button" id="btn_actualizar_capacidad" class="btn btn-warning btn-xl"></button>
                <button type="button" id="btn_cerrar_modal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/capacidad.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('submenu');
        
    jQuery(document).ready(function($){
        jQuery("#tblcapacidad").jqGrid({
            url: 'capacidad/0?grid=capacidad',
            datatype: 'json', mtype: 'GET',
            height: '480px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            colNames: ['ID', 'VALOR','FECHA REGISTRO','ESTADO'],
            rowNum: 10, sortname: 'cap_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_tblcapacidad" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - VALORES DE CAPACIDAD -', align: "center",
            colModel: [
                {name: 'cap_id', index: 'cap_id', align: 'left',width: 10, hidden:true},
                {name: 'cap_val', index: 'cap_val', align: 'center', width: 40},
                {name: 'cap_fecregistro', index: 'cap_fecregistro', align: 'center', width: 40},
                {name: 'cap_estado', index: 'cap_estado', align: 'center', width: 20}
            ],
            pager: '#paginador_tblcapacidad',
            rowList: [10, 20, 30, 40, 50],
            gridComplete: function () {
                    var idarray = jQuery('#tblcapacidad').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tblcapacidad').jqGrid('getDataIDs')[0];
                        $("#tblcapacidad").setSelection(firstid);    
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    $('#btn_modificar_capacidad').click();
                }
                else
                {
                    sin_permiso();
                }
            }
        });

        jQuery.fn.preventDoubleSubmission = function() {
            cambiar_estado_ruta();
        };

    });
</script>
@stop

@endsection


