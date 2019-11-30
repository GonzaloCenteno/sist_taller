
jQuery(document).on("click", "#menu_push", function(){    
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse')) 
    {
        setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblvehiculos').setGridWidth(width);
        }, 300);
    }
    else
    {
       setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblvehiculos').setGridWidth(width);
       }, 300);
    } 
});

var operaciones = {
    buscarPlaca : function(){
        jQuery("#tblvehiculos").jqGrid('setGridParam', {
            url: 'vehiculos/0?grid=vehiculos&busqueda='+$("#txt_buscar_placa").val()
        }).trigger('reloadGrid');
    }
}

function limpiar_datosVehiculo()
{
    $('#txt_placa').val('');
    $('#txt_codigo').val('');
    $('#txt_marca').val('');
    $('#txt_clase').val('');
    $('#txt_carroceria').val('');
    $('#txt_modelo').val('');
    $('#txt_categoria').val('');
}

jQuery(document).on("click", "#btn_nuevo_vehiculo", function() {
    limpiar_datosVehiculo();
    Vehiculo = $('#ModalAgregarVehiculos').modal({backdrop: 'static', keyboard: false});
    Vehiculo.find('.modal-title').text('CREAR NUEVO VEHICULO');
    Vehiculo.find('#btn_crear_vehiculo').html('<i class="fa fa-sign-in"></i> AGREGAR NUEVO').show();
    setTimeout(function (){
        $('#txt_placa').focus();
    }, 200);
});

jQuery(document).on("click", "#btn_crear_vehiculo", function() {
    if ($('#txt_placa').val() == '') {
        mostraralertasconfoco('* EL CAMPO PLACA ES OBLIGATORIO...', '#txt_placa');
        return false;
    }
    if ($('#txt_codigo').val() == '') {
        mostraralertasconfoco('* EL CAMPO CODIGO ES OBLIGATORIO...', '#txt_codigo');
        return false;
    }
    if ($('#txt_marca').val() == '') {
        mostraralertasconfoco('* EL CAMPO MARCA ES OBLIGATORIO...', '#txt_marca');
        return false;
    }
    if ($('#txt_clase').val() == '') {
        mostraralertasconfoco('* EL CAMPO CLASE ES OBLIGATORIO...', '#txt_clase');
        return false;
    }
    if ($('#txt_carroceria').val() == '') {
        mostraralertasconfoco('* EL CAMPO CARROCERIA ES OBLIGATORIO...', '#txt_carroceria');
        return false;
    }
    if ($('#txt_modelo').val() == '') {
        mostraralertasconfoco('* EL CAMPO MODELO ES OBLIGATORIO...', '#txt_modelo');
        return false;
    }
    if ($('#txt_categoria').val() == '') {
        mostraralertasconfoco('* EL CAMPO CATEGORIA ES OBLIGATORIO...', '#txt_categoria');
        return false;
    }
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'vehiculos/create',
        type: 'GET',
        data:
        {
            placa:$('#txt_placa').val(),
            codigo:$('#txt_codigo').val(),
            marca:$('#txt_marca').val(),
            clase:$('#txt_clase').val(),
            carroceria:$('#txt_carroceria').val(),
            modelo:$('#txt_modelo').val(),
            categoria:$('#txt_categoria').val()
        },
        beforeSend:function()
        {            
            MensajeEspera('ENVIANDO INFORMACION');  
        },
        success: function(data) 
        {
            if (data == 1) 
            {
                MensajeConfirmacion('LA RESPUESTA FUE ENVIADA CON EXITO');
                jQuery("#tblvehiculos").jqGrid('setGridParam', {
                    url: 'vehiculos/0?grid=vehiculos'
                }).trigger('reloadGrid');
                $('#btn_cerrar_amodal').click();
            }
            else if(data == 0)
            {
                MensajeAdvertencia('LA PLACA DEL VEHICULO YA FUE REGISTRADA');
                $('#txt_placa').focus();
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

jQuery(document).on("click", "#btn_modificar_vehiculo", function() {
    veh_id = $('#tblvehiculos').jqGrid ('getGridParam', 'selrow');
    if(!veh_id){ mostraralertasconfoco("NO HAY VEHICULOS SELECCIONADOS","#tblvehiculos"); }
    
    Vehiculo = $('#ModalEditarVehiculos').modal({backdrop: 'static', keyboard: false});
    Vehiculo.find('.modal-title').text('EDITAR VEHICULO');
    Vehiculo.find('#btn_actualizar_vehiculo').html('<i class="fa fa-pencil-square-o"></i> MODIFICAR').show();
    setTimeout(function (){
        $('#txt_veh_codigo').focus();
    }, 200);

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'vehiculos/'+veh_id+'?show=datos',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION');  
        },
        success: function(data) 
        {
            $("#txt_veh_codigo").val(data.veh_codigo);
            swal.close();
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
});

jQuery(document).on("click", "#btn_actualizar_vehiculo", function() {
    veh_id = $('#tblvehiculos').jqGrid ('getGridParam', 'selrow');
    if ($('#txt_veh_codigo').val() == '') {
        mostraralertasconfoco('* EL CAMPO CODIGO ES OBLIGATORIO...', '#txt_veh_codigo');
        return false;
    }
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'vehiculos/'+veh_id+'/edit',
        type: 'GET',
        data:
        {
            codigo:$('#txt_veh_codigo').val(),
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
                jQuery("#tblvehiculos").jqGrid('setGridParam', {
                    url: 'vehiculos/0?grid=vehiculos'
                }).trigger('reloadGrid');
                $('#btn_cerrar_emodal').click();
            }
            else if(data == 0)
            {
                MensajeAdvertencia('EL CODIGO DE VEHICULO YA FUE REGISTRADO');
                $('#txt_veh_codigo').focus();
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

jQuery(document).on("click", "#btn_act_tblvehiculos", function(){
    jQuery("#tblvehiculos").jqGrid('setGridParam', {
        url: 'vehiculos/0?grid=vehiculos&busqueda=0'
    }).trigger('reloadGrid');
});

jQuery(document).on("click", "#btn_programacion", function() {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'vehiculos/'+$(this).val()+'/edit',
        type: 'GET',
        data:
        {
            estado:$(this).data("estado"),
            tipo:2
        },
        success: function(data) 
        {
            if (data == 1) 
            {
                MensajeConfirmacion('SE ACTUALIZO EL REGISTRO CON EXITO');
                jQuery("#tblvehiculos").jqGrid('setGridParam', {
                    url: 'vehiculos/0?grid=vehiculos'
                }).trigger('reloadGrid');
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
})