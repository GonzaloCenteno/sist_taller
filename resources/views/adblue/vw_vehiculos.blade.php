@extends('principal.p_inicio')
@section('title', 'VEHICULOS')
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
        <h4 class="m-0"><i class="fa fa-bus fa-2x" aria-hidden="true"></i> REGISTRO DE VEHICULOS</h4>
    </div>
    <div class="card-body">
        <div class="col-lg-12">
            <div class="row">
                @if( $permiso[0]->btn_new == 1 )
                    <div class="col-md-2 text-center">
                        <button id="btn_nuevo_vehiculo" type="button" class="btn btn-xl btn-outline-danger btn-block" readonly="readonly"><i class="fa fa-plus-square"></i> CREAR VEHICULO</button>
                    </div>
                @else
                    <div class="col-md-2 text-center">
                        <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-outline-danger btn-block" readonly="readonly"><i class="fa fa-plus-square"></i> CREAR VEHICULO</button>
                    </div>
                @endif
                @if( $permiso[0]->btn_edit == 1 )
                    <div class="col-md-2 text-center">
                        <button id="btn_modificar_vehiculo" type="button" class="btn btn-xl btn-outline-warning btn-block" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR VEHICULO</button>
                    </div>
                @else
                    <div class="col-md-2 text-center">
                        <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-outline-warning btn-block" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR VEHICULO</button>
                    </div>
                @endif
                    <div class="col-md-4 col-xs-3">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" id="txt_buscar_placa" name="txt_buscar_placa" autofocus="true" autocomplete="off" class="form-control rounded text-center text-uppercase" onClick="this.select()" style="font-weight: bold;" onkeyup="operaciones.buscarPlaca()">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-outline-danger btn-block rounded" onclick="operaciones.buscarPlaca()"><i class="fa fa-search"></i> BUSCAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <br>
        <div class="form-group col-md-12">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblvehiculos"></table>
                <div id="paginador_tblvehiculos"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

<div class="modal fade" id="ModalEditarVehiculos">
    <div class="modal-dialog modal-xs modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>CODIGO:</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                        </div>
                        <input type="text" id="txt_veh_codigo" class="form-control text-center text-uppercase" placeholder="CODIGO VEHICULO" maxlength="20">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_actualizar_vehiculo" class="btn btn-warning btn-xl"></button>
                <button type="button" id="btn_cerrar_emodal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalAgregarVehiculos">
    <div class="modal-dialog modal-xs modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>PLACA:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                </div>
                                <input type="text" id="txt_placa" class="form-control text-center text-uppercase" placeholder="PLACA VEHICULO" maxlength="20">
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>CODIGO:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                </div>
                                <input type="text" id="txt_codigo" class="form-control text-center text-uppercase" placeholder="CODIGO VEHICULO" maxlength="20">
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>MARCA:</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                        </div>
                        <input type="text" id="txt_marca" class="form-control text-center text-uppercase" placeholder="MARCA VEHICULO" maxlength="20">
                    </div>

                </div>
                
                <div class="form-group">
                    <label>CLASE:</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                        </div>
                        <input type="text" id="txt_clase" class="form-control text-center text-uppercase" placeholder="CLASE VEHICULO" maxlength="20">
                    </div>

                </div>
                
                <div class="form-group">
                    <label>CARROCERIA:</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                        </div>
                        <input type="text" id="txt_carroceria" class="form-control text-center text-uppercase" placeholder="CARROCERIA VEHICULO" maxlength="20">
                    </div>

                </div>
                
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>MODELO:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                </div>
                                <input type="text" id="txt_modelo" class="form-control text-center text-uppercase" placeholder="MODELO VEHICULO" maxlength="20">
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>CATEGORIA:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                </div>
                                <input type="text" id="txt_categoria" class="form-control text-center text-uppercase" placeholder="CATEGORIA VEHICULO" maxlength="20">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_crear_vehiculo" class="btn btn-success btn-xl"></button>
                <button type="button" id="btn_cerrar_amodal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/vehiculos.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('submenu');
    
    jQuery(document).ready(function($){
        jQuery("#tblvehiculos").jqGrid({
            url: 'vehiculos/0?grid=vehiculos&busqueda=0',
            datatype: 'json', mtype: 'GET',
            height: '465px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            colNames: ['ID', 'PLACA','CODIGO','MARCA','CLASE / CARROCERIA / MODELO / CATEGORIA','REFERENCIA','PROGRAMACION'],
            rowNum: 15, sortname: 'veh_id', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblvehiculos" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE VEHICULOS -', align: "center",
            colModel: [
                {name: 'veh_id', index: 'veh_id', align: 'left',width: 10, hidden:true},
                {name: 'veh_placa', index: 'veh_placa', align: 'center', width: 10},
                {name: 'veh_codigo', index: 'veh_codigo', align: 'center', width: 10},
                {name: 'veh_marca', index: 'veh_marca', align: 'left', width: 12},
                {name: 'veh_clase', index: 'veh_clase', align: 'left', width: 30},
                {name: 'veh_referencia', index: 'veh_referencia', align: 'left', width: 10},
                {name: 'veh_programacion', index: 'veh_programacion', align: 'center', width: 10, formatter: EstiloProgramacion},
            ],
            pager: '#paginador_tblvehiculos',
            rowList: [15, 20, 30, 40, 50],
            gridComplete: function () {
                    var idarray = jQuery('#tblvehiculos').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tblvehiculos').jqGrid('getDataIDs')[0];
                        $("#tblvehiculos").setSelection(firstid);    
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    $('#btn_modificar_vehiculo').click();
                }
                else
                {
                    sin_permiso();
                }
            }
        });
        
        function EstiloProgramacion(cellValue, options, rowObject) {
            permiso = {!! json_encode($permiso[0]->btn_edit) !!};
            if(permiso == 1)
            {
                var opciones = (cellValue == false) ? '<button id="btn_programacion" value="'+rowObject[0]+'" data-estado="true" type="button" class="btn btn-xl btn-warning btn-round-animate"><i class="fa fa-arrow-down"></i> </button>' : '<button id="btn_programacion" value="'+rowObject[0]+'" data-estado="false" type="button" class="btn btn-xl btn-danger btn-round-animate"><i class="fa fa-arrow-up"></i> </button>';
            }
            else
            {
                var opciones = (cellValue == false) ? '<button onclick="sin_permiso()" type="button" class="btn btn-xl btn-warning btn-round-animate"><i class="fa fa-arrow-down"></i> </button>' : '<button onclick="sin_permiso()" type="button" class="btn btn-xl btn-danger btn-round-animate"><i class="fa fa-arrow-up"></i> </button>';
            }
            return opciones;
        }
    });
</script>
@stop

@endsection


