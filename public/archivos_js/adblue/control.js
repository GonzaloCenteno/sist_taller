
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
            if (data == 1) 
            {
                $("#txt_cingreso").val('');
                $("#txt_cobservaciones").val('');
                MensajeConfirmacion('SE GENERO EL CONTROL CON EXITO');
                jQuery("#tblcontrol").jqGrid('setGridParam', {
                    url: 'control/0?grid=control'
                }).trigger('reloadGrid');
            }
            else
            {
                MensajeAdvertencia('NO SE PUDO ENVIAR LA RESPUESTA');
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

function modificar_fecha_control()
{
    ModificarControl = $('#ModalCambiarFecha').modal({backdrop: 'static', keyboard: false});
    ModificarControl.find('.modal-title').text('EDITAR FECHA CONTROL');
    ModificarControl.find('#btn_actualizar_fecha').html('<i class="fa fa-pencil-square-o"></i> MODIFICAR').show();
    $('#txt_con_fecregistro').val('');
}

jQuery(document).on("click", "#btn_actualizar_fecha", function(){
    con_id = $('#tblcontrol').jqGrid ('getGridParam', 'selrow');

    if ($('#txt_con_fecregistro').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA ES OBLIGATORIO...', '#txt_con_fecregistro');
        return false;
    }
    
    $.ajax({
        url: 'control/'+con_id+'/edit',
        type: 'GET',
        data:
        {
            con_fecregistro:$('#txt_con_fecregistro').val(),
        },
        beforeSend:function()
        {            
            MensajeEspera('ENVIANDO INFORMACION');  
        },
        success: function(data) 
        {
            if (data == 1) 
            {
                MensajeConfirmacion('SE ACTUALIZO EL REGISTRO CON EXITO');
                jQuery("#tblcontrol").jqGrid('setGridParam', {
                    url: 'control/0?grid=control'
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal_fecha').click();
            }
            else
            {
                MensajeAdvertencia('NO SE PUDO ENVIAR LA RESPUESTA');
                console.log(data);
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
});
    