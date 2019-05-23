jQuery(document).ready(function ($) {
    
    jQuery("#tblcontrol").jqGrid({
        url: 'control/0?grid=control',
        datatype: 'json', mtype: 'GET',
        height: '450px', autowidth: true,
        toolbarfilter: true,
        sortable: false,
        colNames: ['ID', 'FECHA', 'INGRESO ISOTANQUE AL AREA', 'TOTAL SALIDA POR ISOTANQUE', 'STOP', 'EXCEDENTE POR ISOTANQUE','CANTIDAD','OBSERVACIONES'],
        rowNum: 20, sortname: 'xcon_id', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblcontrol" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - CONTROL DIARIO DE ADBLUE AREQUIPA LITROS -', align: "center",
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
        rowList: [10, 20, 30, 40, 50]
    });
});

jQuery(document).on("click", "#menu_push", function(){    
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse')) 
    {
        setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblcontrol').setGridWidth(width);
        }, 300);
    }
    else
    {
       setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblcontrol').setGridWidth(width);
       }, 300);
    } 
});

function autocompletar_estaciones(textbox){
    $.ajax({
        type: 'GET',
        url: 'ruta_estacion/0?busqueda=estaciones',
        success: function (data) {
            var $datos = data;
            $("#" + textbox).autocomplete({   
                source: $datos,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    return false;
                }
            });
        }
    });
}

function autocompletar_placas(textbox){
    $.ajax({
        type: 'GET',
        url: 'control/0?busqueda=placas',
        success: function (data) {
            var $datos = data;
            $("#" + textbox).autocomplete({   
                source: $datos,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    return false;
                }
            });
        }
    });
}

jQuery(document).on("click", "#btn_nuevo_control", function () {
    if ($('#txt_cingreso').val() == '') {
        mostraralertasconfoco('* EL CAMPO CANTIDAD ES OBLIGATORIO...', '#txt_cingreso');
        return false;
    }

    swal({
        title: '¿DESEA GENERAR EL CONTROL AHORA?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ACEPTAR',
        cancelButtonText: 'CANCELAR',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true,
        allowOutsideClick: false,
        allowEscapeKey:false,
    }).then(function (result) {
        generar_control();
    }, function (dismiss) {
        
    });
});

function generar_control()
{
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'control/create',
        type: 'GET',
        data:
        {
            cantidad: $('#txt_cingreso').val(),
            observacion: $('#txt_cobservaciones').val()
        },
        beforeSend: function ()
        {
            MensajeEspera('ENVIANDO INFORMACION');
        },
        success: function (data)
        {
            if (data[0].control_salida == 'OK')
            {
                MensajeConfirmacion('SE GENERO EL CONTROL CON EXITO');
                $("#txt_cingreso").val('');
                $("#txt_cobservaciones").val('');
                jQuery("#tblcontrol").jqGrid('setGridParam', {
                    url: 'control/0?grid=control'
                }).trigger('reloadGrid');
            }
        },
        error: function (data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

jQuery(document).on("click", "#btn_act_tblcontrol", function () {
    $("#txt_cingreso").val('');
    $("#txt_cobservaciones").val('');
    jQuery("#tblcontrol").jqGrid('setGridParam', {
        url: 'control/0?grid=control'
    }).trigger('reloadGrid');
});

function limpiar_datos_rep_abastecimiento()
{
    $(".modal_rep").val('');
}

jQuery(document).on("click", "#btn_ctr_abastecimiento", function(){
    limpiar_datos_rep_abastecimiento();
    Abastecimiento = $('#ModalRepCtrlAbast').modal({backdrop: 'static', keyboard: false});
    Abastecimiento.find('.modal-title').text('CONTROL DE ABASTECIMIENTO DE ADBLUE');
    Abastecimiento.find('#btn_abrir_reporte').html('<i class="fa fa-sign-in"></i> ABRIR REPORTE').show();
    setTimeout(function (){
        $('#mdl_txt_estacion').focus();
    }, 200);
    autocompletar_estaciones('mdl_txt_estacion');
    autocompletar_placas('mdl_txt_placa');
});

jQuery(document).on("click", "#btn_abrir_reporte", function(){
    if ($('#hiddenmdl_txt_estacion').val() == '') {
        mostraralertasconfoco('* EL CAMPO ESTACION ES OBLIGATORIO...', '#mdl_txt_estacion');
        return false;
    }
    
    if ($('#hiddenmdl_txt_placa').val() == '') {
        mostraralertasconfoco('* EL CAMPO PLACA ES OBLIGATORIO...', '#mdl_txt_placa');
        return false;
    }
    
    window.open('control_abast_xplaca/'+$("#hiddenmdl_txt_estacion").val()+'/'+$("#hiddenmdl_txt_placa").val());
});

jQuery(document).on("click", "#btn_ctr_consumo", function(){
    Consumo = $('#ModalRepCtrlConsumo').modal({backdrop: 'static', keyboard: false});
    Consumo.find('.modal-title').text('CONTROL DE CONSUMOS');
    Consumo.find('#btn_abrir_reporte_ctrlcon').html('<i class="fa fa-sign-in"></i> ABRIR REPORTE').show();
});

jQuery(document).on("click", "#btn_abrir_reporte_ctrlcon", function(){
    window.open('control_consumo_rep/'+$("#cbx_ctrlcon_anio").val()+'/'+$("#cbx_ctrlcon_mes").val());
});