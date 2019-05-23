
jQuery(document).on("click", "#menu_push", function(){    
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse')) 
    {
        setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblestaciones').setGridWidth(width);
        }, 300);
    }
    else
    {
       setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblestaciones').setGridWidth(width);
       }, 300);
    } 
});

function limpiar_datosEstacion()
{
    $('#txt_est_descripcion').val('');
}

jQuery(document).on("click", "#btn_nueva_estacion", function() {
    limpiar_datosEstacion();
    Estacion = $('#ModalEstaciones').modal({backdrop: 'static', keyboard: false});
    Estacion.find('.modal-title').text('CREAR NUEVA ESTACION');
    Estacion.find('#btn_crear_estacion').html('<i class="fa fa-sign-in"></i> AGREGAR NUEVO').show();
    Estacion.find('#btn_actualizar_estacion').hide();
    setTimeout(function (){
        $('#txt_est_descripcion').focus();
    }, 200);
});

jQuery(document).on("click", "#btn_crear_estacion", function() {
    if ($('#txt_est_descripcion').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#txt_est_descripcion');
        return false;
    }
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'estacion/create',
        type: 'GET',
        data:
        {
            descripcion:$('#txt_est_descripcion').val()
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
                jQuery("#tblestaciones").jqGrid('setGridParam', {
                    url: 'estacion/0?grid=estaciones'
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal').click();
            }
            else if(data == 0)
            {
                MensajeAdvertencia('LA ESTACION YA FUE REGISTRADA');
                $('#txt_est_descripcion').focus();
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

jQuery(document).on("click", "#btn_modificar_estacion", function() {
    est_id = $('#tblestaciones').jqGrid ('getGridParam', 'selrow');
    if(est_id){
        Estacion = $('#ModalEstaciones').modal({backdrop: 'static', keyboard: false});
        Estacion.find('.modal-title').text('EDITAR ESTACION');
        Estacion.find('#btn_actualizar_estacion').html('<i class="fa fa-pencil-square-o"></i> MODIFICAR').show();
        Estacion.find('#btn_crear_estacion').hide();
        setTimeout(function (){
            $('#txt_est_descripcion').focus();
        }, 200);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'estacion/'+est_id+'?show=datos',
            type: 'GET',
            beforeSend:function()
            {            
                MensajeEspera('RECUPERANDO INFORMACION');  
            },
            success: function(data) 
            {
                $("#txt_est_descripcion").val(data[0].est_descripcion);
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
        mostraralertasconfoco("NO HAY ESTACIONES SELECCIONADAS","#tblestaciones");
    }
});

jQuery(document).on("click", "#btn_actualizar_estacion", function() {
    est_id = $('#tblestaciones').jqGrid ('getGridParam', 'selrow');
    if ($('#txt_est_descripcion').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#txt_est_descripcion');
        return false;
    }
    
    $.ajax({
        url: 'estacion/'+est_id+'/edit',
        type: 'GET',
        data:
        {
            descripcion:$('#txt_est_descripcion').val(),
            tipo:1
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
                jQuery("#tblestaciones").jqGrid('setGridParam', {
                    url: 'estacion/0?grid=estaciones'
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal').click();
            }
            else if(data == 0)
            {
                MensajeAdvertencia('LA ESTACION YA FUE REGISTRADA');
                $('#txt_est_descripcion').focus();
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

function cambiar_estado_estacion(est_id,estado)
{
    $.ajax({
        url: 'estacion/'+est_id+'/edit',
        type: 'GET',
        data:
        {
            estado: estado,
            tipo:2
        },
       
        success: function(data) 
        {
            if (data == 1) 
            {
                MensajeConfirmacion("CAMBIO DE ESTADO EXITOSO");
                jQuery("#tblestaciones").jqGrid('setGridParam', {
                    url: 'estacion/0?grid=estaciones'
                }).trigger('reloadGrid');
            }
            else
            {
                MensajeAdvertencia('OCURRIO UN PROBLEMA AL ENVIAR LA INFORMACION');
                console.log(data);
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

jQuery(document).on("click", "#btn_act_tblestacion", function(){
    jQuery("#tblestaciones").jqGrid('setGridParam', {
        url: 'estacion/0?grid=estaciones'
    }).trigger('reloadGrid');
});