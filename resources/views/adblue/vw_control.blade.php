@extends('principal.p_inicio')
@section('title', 'CONTROL')
@section('content')
<style>

    .column_red {
        background-color: #c83839;
    }
    
    th.ui-th-column div{
        white-space:normal !important;
        height:auto !important;
        padding:2px;
    }
    
    .clsDatePicker {
        z-index: 100000 !important;
    }
</style>
<br>
<div class="card card-danger card-outline">
    <div class="card-header">
        <h4 class="m-0">CONTROL DIARIO DE ADBLUE - AREQUIPA LITROS</h4>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>CANTIDAD EN LITROS:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-share"></i></span>
                        </div>
                        <input type="text" id="txt_cingreso" class="form-control text-center" onkeypress="return soloNumeroTab(event);" maxlength="8">
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label>OBSERVACIONES:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-share"></i></span>
                        </div>
                        <input type="text" id="txt_cobservaciones" class="form-control text-center text-uppercase">
                    </div>
                </div>
            </div>
            @if( $permiso[0]->btn_new == 1 )
                <div class="col-md-2 text-center" style="padding-top: 31px;">
                    <button id="btn_nuevo_control" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus-square"></i> AGREGAR</button>
                </div>
            @else
                <div class="col-md-2 text-center" style="padding-top: 31px;">
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus-square"></i> AGREGAR</button>
                </div>
            @endif
            <div class="col-md-3" style="padding-top: 31px;">
                <div class="btn-group">
                    <button type="button" class="btn btn-warning"><i class="fa fa-print"></i> IMPRIMIR</button>
                    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        @if( $permiso[0]->btn_print == 1 )
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('control_diario') }}" class="btn btn-xl" target="_blank"> CONTROL DIARIO DE ADBLUE - AREQUIPA</a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('control_abastecimiento') }}" class="btn btn-xl" target="_blank"> CONTROL ABASTECIMIENTOS DE ADBLUE POR CIUDAD</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" id="btn_ctr_abastecimiento" class="btn btn-xl"> CONTROL ABASTECIMIENTOS DE ADBLUE POR PLACA</a>
    <!--                        <div class="dropdown-divider"></div>
                            <a href="#" id="btn_ctr_consumo" class="btn btn-xl"> CONTROL CONSUMOS</a>-->
                            <div class="dropdown-divider"></div>
                        @else
                            <div class="dropdown-divider"></div>
                            <a onclick="sin_permiso();" class="btn btn-xl" target="_blank"> CONTROL DIARIO DE ADBLUE - AREQUIPA</a>
                            <div class="dropdown-divider"></div>
                            <a onclick="sin_permiso();" class="btn btn-xl" target="_blank"> CONTROL ABASTECIMIENTOS DE ADBLUE POR CIUDAD</a>
                            <div class="dropdown-divider"></div>
                            <a onclick="sin_permiso();" class="btn btn-xl"> CONTROL ABASTECIMIENTOS DE ADBLUE POR PLACA</a>
    <!--                        <div class="dropdown-divider"></div>
                            <a href="#" id="btn_ctr_consumo" class="btn btn-xl"> CONTROL CONSUMOS</a>-->
                            <div class="dropdown-divider"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group col-md-12">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblcontrol"></table>
                <div id="paginador_tblcontrol"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

<div class="modal fade" id="ModalRepCtrlAbast">
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
                                    <label>ESTACION:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="hidden" id="hiddenmdl_txt_estacion" name="hiddenmdl_txt_estacion" class="modal_rep">
                                        <input type="text" id="mdl_txt_estacion" class="form-control text-center text-uppercase modal_rep" placeholder="ESCRIBIR NOMBRE DE LA ESTACION">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PLACA:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <input type="hidden" id="hiddenmdl_txt_placa" name="hiddenmdl_txt_placa" class="modal_rep">
                                        <input type="text" id="mdl_txt_placa" class="form-control text-center text-uppercase modal_rep" placeholder="ESCRIBIR NUMERO DE LA PLACA">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_abrir_reporte" class="btn btn-success btn-xl"></button>
                <button type="button" id="btn_cerrar_modal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalRepCtrlConsumo">
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

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>AÑO:</label>

                                    <select class="form-control" style="width: 100%;" id="cbx_ctrlcon_anio" name="cbx_ctrlcon_anio">
                                        <option value="2019"> 2019 </option>
                                        <option value="2020"> 2020 </option>
                                        <option value="2021"> 2021 </option>
                                        <option value="2022"> 2022 </option>
                                        <option value="2023"> 2023 </option>
                                    </select>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>MES:</label>

                                    <select class="form-control" style="width: 100%;" id="cbx_ctrlcon_mes" name="cbx_ctrlcon_mes">
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

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_abrir_reporte_ctrlcon" class="btn btn-success btn-xl"></button>
                <button type="button" id="btn_cerrar_modal_ctrlcon" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalCambiarFecha">
    <div class="modal-dialog modal-xs modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body ui-front">
                <div class="form-group">
                    <label>FECHA INGRESO:</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                        <input type="text" id="txt_con_fecregistro" class="form-control text-center FechaControl clsDatePicker" readonly="readonly">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_actualizar_fecha" class="btn btn-warning btn-xl"></button>
                <button type="button" id="btn_cerrar_modal_fecha" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/control.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('active');
    
    jQuery(document).ready(function ($) {
    
        jQuery("#tblcontrol").jqGrid({
            url: 'control/0?grid=control',
            datatype: 'json', mtype: 'GET',
            height: '450px', autowidth: true,
            toolbarfilter: true,
            sortable: false,
            colNames: ['ID', 'FECHA', 'INGRESO ISOTANQUE AL AREA', 'TOTAL SALIDA POR ISOTANQUE', 'STOP', 'EXCEDENTE POR ISOTANQUE','CANTIDAD','OBSERVACIONES'],
            rowNum: 30, sortname: 'xcon_id', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblcontrol" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - CONTROL DIARIO DE ADBLUE AREQUIPA LITROS -', align: "center",
            colModel: [
                {name: 'xcon_id', index: 'xcon_id', align: 'left', width: 10, hidden: true},
                {name: 'xfecha', index: 'xfecha', align: 'center', width: 10, formatter: 'date', formatoptions: {srcformat: 'Y-m-d', newformat: 'd/m/Y'}},
                {name: 'xing_isotanque', index: 'xing_isotanque', align: 'center', width: 15},
                {name: 'xtotal_sal_isotanq', index: 'xtotal_sal_isotanq', align: 'center', width: 15},
                {name: 'xstop', index: 'xstop', align: 'center', width: 10},
                {name: 'xexce_isotanq', index: 'xexce_isotanq', align: 'center', width: 15},
                {name: 'xcantidad', index: 'xcantidad', align: 'center', width: 10, classes: 'column_red'},
                {name: 'xcon_observacion', index: 'xcon_observacion', align: 'center', width: 35}
            ],
            pager: '#paginador_tblcontrol',
            rowList: [30, 50, 70, 90],
            subGrid: true,
            subGridRowExpanded: MostrarDetalle,
            subGridOptions : {
                reloadOnExpand :false,
                selectOnExpand : true 
            },
            ondblClickRow: function ()
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    modificar_fecha_control();
                }
                else
                {
                    sin_permiso();
                }
            }
        });
        
    });
    
    function MostrarDetalle(parentRowID, parentRowKey) {
        var childGridID = parentRowID + "_table";
        var childGridPagerID = parentRowID + "_pager";

        $('#' + parentRowID).append('<table id=' + childGridID + '></table><div id=' + childGridPagerID + ' class=scroll></div>');

        $("#" + childGridID).jqGrid({
            url: 'control/0?grid=detalle_control&con_id='+parentRowKey,
            mtype: "GET",
            datatype: "json",
            toolbarfilter: true,rowNum: 10,
            sortable: false,
            viewrecords: true,
            rownumbers: true,
            sortname: 'cde_fecha', sortorder: 'asc',
            colNames: ['ID', 'FECHA','ESTACION','PLACA','CONSUMO DEL DIA'],
            colModel: [
                {name: 'cde_id', index: 'cde_id', align: 'left',width: 10, hidden:true},
                {name: 'cde_fecha', index: 'cde_fecha', align: 'center', width: 10},
                {name: 'est_descripcion', index: 'est_descripcion', align: 'left', width: 20},
                {name: 'veh_placa', index: 'veh_placa', align: 'center', width: 10},
                {name: 'cde_qabastecida', index: 'cde_qabastecida', align: 'center', width: 15}
            ],
            pager: childGridPagerID,
            rowList: [10, 20, 30, 40],
            width: 1250,
            height: '200%',
        });
    }
    
    $(document).on("focus", ".FechaControl", function(){
        $(this).datepicker({ minDate: -20,dateFormat: 'dd-mm-yy',showAnim: 'clip' });

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
</script>
@stop

@endsection


