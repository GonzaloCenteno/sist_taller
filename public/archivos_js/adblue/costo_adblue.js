
jQuery(document).on("click", "#menu_push", function(){    
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse')) 
    {
        setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblcostos_adblue').setGridWidth(width);
        }, 300);
    }
    else
    {
       setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblcostos_adblue').setGridWidth(width);
       }, 300);
    } 
});

function limpiar_datosCostosAdblue()
{
    $('#txt_coa_costo').val('');
    $('#cbx_coa_anio_mdl').val($("#cbx_coa_anio").val());
    var mes = new Date();
    $("#cbx_coa_mes_mdl").val(parseInt(mes.getMonth()) + 1);
    $("#cbx_coa_mes_mdl").removeAttr('disabled');
}

jQuery(document).on("click", "#btn_nuevo_costo_adblue", function() {
    limpiar_datosCostosAdblue();
    Costos = $('#ModalCostosAdblue').modal({backdrop: 'static', keyboard: false});
    Costos.find('.modal-title').text('CREAR NUEVO COSTO');
    Costos.find('#btn_crear_costo_adblue').html('<i class="fa fa-sign-in"></i> AGREGAR NUEVO').show();
    Costos.find('#btn_actualizar_costo_adblue').hide();
    
    setTimeout(function (){
        $('#txt_coa_costo').focus();
    }, 200);
});

jQuery(document).on("click", "#btn_crear_costo_adblue", function() {
    if ($('#txt_coa_costo').val() == '') {
        mostraralertasconfoco('* EL CAMPO COSTO ES OBLIGATORIO...', '#txt_coa_costo');
        return false;
    }
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'costo_adblue/create',
        type: 'GET',
        data:
        {
            anio:$('#cbx_coa_anio_mdl').val(),
            mes:$('#cbx_coa_mes_mdl').val(),
            costo:$('#txt_coa_costo').val()
        },
        beforeSend:function()
        {            
            MensajeEspera('ENVIANDO INFORMACION');  
        },
        success: function(data) 
        {
            if (data == 1) 
            {
                MensajeConfirmacion('EL RESPUESTA FUE ENVIADA CON EXITO');
                jQuery("#tblcostos_adblue").jqGrid('setGridParam', {
                    url: 'costo_adblue/0?grid=costos_adblue&anio='+$("#cbx_coa_anio").val(),
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal').click();
            }
            else if(data == 0)
            {
                MensajeAdvertencia('YA EXISTE UN COSTO PARA EL AÑO Y MES SELECCIONADO');
                $('#txt_coa_costo').focus();
                console.log(data);
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

jQuery(document).on("click", "#btn_modificar_costo_adblue", function() {
    coa_id = $('#tblcostos_adblue').jqGrid ('getGridParam', 'selrow');
    if(coa_id){
        Costos = $('#ModalCostosAdblue').modal({backdrop: 'static', keyboard: false});
        Costos.find('.modal-title').text('EDITAR COSTO ADBLUE');
        Costos.find('#btn_actualizar_costo_adblue').html('<i class="fa fa-pencil-square-o"></i> MODIFICAR').show();
        Costos.find('#btn_crear_costo_adblue').hide();
        setTimeout(function (){
            $('#txt_coa_costo').focus();
        }, 200);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'costo_adblue/'+coa_id+'?show=datos',
            type: 'GET',
            beforeSend:function()
            {            
                MensajeEspera('RECUPERANDO INFORMACION');  
            },
            success: function(data) 
            {
                $("#cbx_coa_anio_mdl").val(data[0].coa_anio);
                $("#cbx_coa_mes_mdl").val(data[0].coa_mes);
                $("#cbx_coa_mes_mdl").attr('disabled','true');
                $("#txt_coa_costo").val(data[0].coa_saldo);
                swal.close();
            },
            error: function(data) {
                MensajeAdvertencia("hubo un error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
            }
        });
    }
    else{
        mostraralertasconfoco("NO HAY REGISTROS SELECCIONADOS","#tblcostos_adblue");
    }
});

jQuery(document).on("click", "#btn_actualizar_costo_adblue", function() {
    coa_id = $('#tblcostos_adblue').jqGrid ('getGridParam', 'selrow');
    if ($('#txt_coa_costo').val() == '') {
        mostraralertasconfoco('* EL CAMPO COSTO ES OBLIGATORIO...', '#txt_coa_costo');
        return false;
    }
    
    $.ajax({
        url: 'costo_adblue/'+coa_id+'/edit',
        type: 'GET',
        data:
        {
            costo:$('#txt_coa_costo').val()
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
                jQuery("#tblcostos_adblue").jqGrid('setGridParam', {
                    url: 'costo_adblue/0?grid=costos_adblue&anio='+$("#cbx_coa_anio").val(),
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal').click();
            }
            else if(data == 0)
            {
                MensajeAdvertencia('YA EXISTE UN COSTO PARA EL AÑO Y MES SELECCIONADO');
                $('#txt_coa_costo').focus();
                console.log(data);
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

jQuery(document).on("click", "#btn_act_tblcostosadblue", function(){
    var anio_actual = new Date();
    $("#cbx_coa_anio").val(anio_actual.getFullYear());
    $("#cbx_coa_anio").change();

});

jQuery(document).on("change", "#cbx_coa_anio", function(){
    jQuery("#tblcostos_adblue").jqGrid('setGridParam', {
        url: 'costo_adblue/0?grid=costos_adblue&anio='+$(this).val()
    }).trigger('reloadGrid');
});