jQuery(document).ready(function ($) {
    $('#men_registro').addClass('menu-open');
    $('a[href*="#1"]').addClass('active');
    $('.control').addClass('active');

    jQuery("#tblcontrol").jqGrid({
        url: 'control/0?grid=control',
        datatype: 'json', mtype: 'GET',
        height: '500px', autowidth: true,
        toolbarfilter: true,
        sortable: false,
        colNames: ['ID', 'FECHA', 'INGRESO', 'TOTAL SALIDA', 'STOP', 'CANTIDAD EN LITROS'],
        rowNum: 20, sortname: 'con_id', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblcontrol" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - CONTROL DIARIO DE ADBLUE AREQUIPA LITROS -', align: "center",
        colModel: [
            {name: 'con_id', index: 'con_id', align: 'left', width: 10, hidden: true},
            {name: 'con_fecregistro', index: 'con_fecregistro', align: 'center', width: 15, formatter: 'date', formatoptions: {srcformat: 'Y-m-d', newformat: 'd/m/Y'}},
            {name: 'con_ingreso', index: 'con_ingreso', align: 'center', width: 15},
            {name: 'con_totsalida', index: 'con_totsalida', align: 'center', width: 15},
            {name: 'con_stop', index: 'con_stop', align: 'center', width: 15},
            {name: 'con_cantidad', index: 'con_cantidad', align: 'center', width: 15, classes: 'column_red'}
        ],
        pager: '#paginador_tblcontrol',
        rowList: [10, 20, 30, 40, 50]
    });
});

jQuery(document).on("click", "#menu_push", function () {
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse'))
    {
        $("#tblcontrol").jqGrid('setGridWidth', 1520);
    } else
    {
        $("#tblcontrol").jqGrid('setGridWidth', 1327);
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
                    cantidad: $('#txt_cingreso').val()
                },
        beforeSend: function ()
        {
            MensajeEspera('ENVIANDO INFORMACION');
        },
        success: function (data)
        {
            if (data[0].control_total_salida1 == 'OK')
            {
                MensajeConfirmacion('SE GENERO EL CONTROL CON EXITO');
                $("#txt_cingreso").val('');
                jQuery("#tblcontrol").jqGrid('setGridParam', {
                    url: 'control/0?grid=control'
                }).trigger('reloadGrid');
            } else
            {
                MensajeAdvertencia(data[0].control_total_salida1);
                $("#txt_cingreso").val('');
                console.log(data);
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