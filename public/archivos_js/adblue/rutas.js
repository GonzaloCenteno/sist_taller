jQuery(document).ready(function($){
    $('#men_registro').addClass('menu-open');
    $('a[href*="#1"]').addClass('active');
    $('.ruta').addClass('active');
    
    jQuery("#tblrutas").jqGrid({
        url: 'ruta/0?grid=rutas',
        datatype: 'json', mtype: 'GET',
        height: '512px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        colNames: ['ID', 'DESCRIPCION','FECHA REGISTRO','ESTADO'],
        rowNum: 10, sortname: 'rut_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_tblruta" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE RUTAS -', align: "center",
        colModel: [
            {name: 'rut_id', index: 'rut_id', align: 'left',width: 10, hidden:true},
            {name: 'rut_descripcion', index: 'rut_descripcion', align: 'left', width: 60},
            {name: 'rut_fecregistro', index: 'rut_fecregistro', align: 'center', width: 15},
            {name: 'rut_estado', index: 'rut_estado', align: 'center', width: 10}
        ],
        pager: '#paginador_tblrutas',
        rowList: [10, 20, 30, 40, 50],
        gridComplete: function () {
                var idarray = jQuery('#tblrutas').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                var firstid = jQuery('#tblrutas').jqGrid('getDataIDs')[0];
                    $("#tblrutas").setSelection(firstid);    
                }
            },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){$('#btn_modificar_ruta').click();}
    });
    
    $(window).on('resize.jqGrid', function () {
        $("#tblrutas").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
    jQuery.fn.preventDoubleSubmission = function() {
        cambiar_estado_ruta();
    };
    
});

function limpiar_datosRuta()
{
    $('#txt_rut_descripcion').val('');
}

jQuery(document).on("click", "#btn_nueva_ruta", function() {
    limpiar_datosRuta();
    Ruta = $('#ModalRutas').modal({backdrop: 'static', keyboard: false});
    Ruta.find('.modal-title').text('CREAR NUEVA RUTA');
    Ruta.find('#btn_crear_ruta').html('<i class="fa fa-sign-in"></i> AGREGAR NUEVO').show();
    Ruta.find('#btn_actualizar_ruta').hide();
    setTimeout(function (){
        $('#txt_rut_descripcion').focus();
    }, 200);
});

jQuery(document).on("click", "#btn_crear_ruta", function() {
    if ($('#txt_rut_descripcion').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#txt_rut_descripcion');
        return false;
    }
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'ruta/create',
        type: 'GET',
        data:
        {
            descripcion:$('#txt_rut_descripcion').val()
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
                jQuery("#tblrutas").jqGrid('setGridParam', {
                    url: 'ruta/0?grid=rutas'
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal').click();
            }
            else if(data == 0)
            {
                MensajeAdvertencia('LA RUTA YA FUE REGISTRADA');
                $('#txt_rut_descripcion').focus();
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

jQuery(document).on("click", "#btn_modificar_ruta", function() {
    rut_id = $('#tblrutas').jqGrid ('getGridParam', 'selrow');
    if(rut_id){
        Ruta = $('#ModalRutas').modal({backdrop: 'static', keyboard: false});
        Ruta.find('.modal-title').text('EDITAR RUTA');
        Ruta.find('#btn_actualizar_ruta').html('<i class="fa fa-pencil-square-o"></i> MODIFICAR').show();
        Ruta.find('#btn_crear_ruta').hide();
        setTimeout(function (){
            $('#txt_rut_descripcion').focus();
        }, 200);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'ruta/'+rut_id+'?show=datos',
            type: 'GET',
            beforeSend:function()
            {            
                MensajeEspera('RECUPERANDO INFORMACION');  
            },
            success: function(data) 
            {
                $("#txt_rut_descripcion").val(data[0].rut_descripcion);
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
        mostraralertasconfoco("NO HAY RUTAS SELECCIONADAS","#tblrutas");
    }
});

jQuery(document).on("click", "#btn_actualizar_ruta", function() {
    rut_id = $('#tblrutas').jqGrid ('getGridParam', 'selrow');
    if ($('#txt_rut_descripcion').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#txt_rut_descripcion');
        return false;
    }
    
    $.ajax({
        url: 'ruta/'+rut_id+'/edit',
        type: 'GET',
        data:
        {
            descripcion:$('#txt_rut_descripcion').val(),
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
                jQuery("#tblrutas").jqGrid('setGridParam', {
                    url: 'ruta/0?grid=rutas'
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal').click();
            }
            else if(data == 0)
            {
                MensajeAdvertencia('LA RUTA YA FUE REGISTRADA');
                $('#txt_rut_descripcion').focus();
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

function cambiar_estado_ruta(rut_id,estado)
{
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'ruta/'+rut_id+'/edit',
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
                jQuery("#tblrutas").jqGrid('setGridParam', {
                    url: 'ruta/0?grid=rutas'
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

jQuery(document).on("click", "#btn_act_tblruta", function(){
    jQuery("#tblrutas").jqGrid('setGridParam', {
        url: 'ruta/0?grid=rutas'
    }).trigger('reloadGrid');
});