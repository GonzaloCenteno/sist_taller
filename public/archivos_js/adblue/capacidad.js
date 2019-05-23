
jQuery(document).on("click", "#menu_push", function(){    
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse')) 
    {
        setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblcapacidad').setGridWidth(width);
        }, 300);
    }
    else
    {
       setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblcapacidad').setGridWidth(width);
       }, 300);
    } 
});

function limpiar_datosCapacidad()
{
    $('#txt_cap_valor').val('');
}

jQuery(document).on("click", "#btn_nueva_capacidad", function() {
    limpiar_datosCapacidad();
    Capacidad = $('#ModalCapacidad').modal({backdrop: 'static', keyboard: false});
    Capacidad.find('.modal-title').text('CREAR NUEVA CAPACIDAD');
    Capacidad.find('#btn_crear_capacidad').html('<i class="fa fa-sign-in"></i> AGREGAR NUEVO').show();
    Capacidad.find('#btn_actualizar_capacidad').hide();
    setTimeout(function (){
        $('#txt_cap_valor').focus();
    }, 200);
});

jQuery(document).on("click", "#btn_crear_capacidad", function() {
    if ($('#txt_cap_valor').val() == '') {
        mostraralertasconfoco('* EL CAMPO VALOR ES OBLIGATORIO...', '#txt_cap_valor');
        return false;
    }
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'capacidad/create',
        type: 'GET',
        data:
        {
            valor:$('#txt_cap_valor').val()
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
                jQuery("#tblcapacidad").jqGrid('setGridParam', {
                    url: 'capacidad/0?grid=capacidad'
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal').click();
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

jQuery(document).on("click", "#btn_modificar_capacidad", function() {
    cap_id = $('#tblcapacidad').jqGrid ('getGridParam', 'selrow');
    if(cap_id){
        Capacidad = $('#ModalCapacidad').modal({backdrop: 'static', keyboard: false});
        Capacidad.find('.modal-title').text('EDITAR CAPACIDAD');
        Capacidad.find('#btn_actualizar_capacidad').html('<i class="fa fa-pencil-square-o"></i> MODIFICAR').show();
        Capacidad.find('#btn_crear_capacidad').hide();
        setTimeout(function (){
            $('#txt_cap_valor').focus();
        }, 200);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'capacidad/'+cap_id+'?show=datos',
            type: 'GET',
            beforeSend:function()
            {            
                MensajeEspera('RECUPERANDO INFORMACION');  
            },
            success: function(data) 
            {
                $("#txt_cap_valor").val(data[0].cap_val);
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
        mostraralertasconfoco("NO HAY REGISTROS SELECCIONADOS","#tblcapacidad");
    }
});

jQuery(document).on("click", "#btn_actualizar_capacidad", function() {
    cap_id = $('#tblcapacidad').jqGrid ('getGridParam', 'selrow');
    if ($('#txt_cap_valor').val() == '') {
        mostraralertasconfoco('* EL CAMPO VALOR ES OBLIGATORIO...', '#txt_cap_valor');
        return false;
    }
    
    $.ajax({
        url: 'capacidad/'+cap_id+'/edit',
        type: 'GET',
        data:
        {
            valor:$('#txt_cap_valor').val(),
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
                jQuery("#tblcapacidad").jqGrid('setGridParam', {
                    url: 'capacidad/0?grid=capacidad'
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal').click();
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

function cambiar_estado_capacidad(cap_id,estado)
{
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'capacidad/'+cap_id+'/edit',
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
                jQuery("#tblcapacidad").jqGrid('setGridParam', {
                    url: 'capacidad/0?grid=capacidad'
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

jQuery(document).on("click", "#btn_act_tblcapacidad", function(){
    jQuery("#tblcapacidad").jqGrid('setGridParam', {
        url: 'capacidad/0?grid=capacidad'
    }).trigger('reloadGrid');
});