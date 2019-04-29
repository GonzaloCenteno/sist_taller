jQuery(document).ready(function($){
    $('#men_registro').addClass('menu-open');
    $('a[href*="#1"]').addClass('active');
    $('.costo_adblue').addClass('active');
    
    var anio = new Date();
    $("#cbx_coa_anio").val(anio.getFullYear());

    jQuery("#tblcostos_adblue").jqGrid({
        url: 'costo_adblue/0?grid=costos_adblue',
        datatype: 'json', mtype: 'GET',
        height: '450px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        colNames: ['ID', 'AÑO','MES','COSTO','FECHA REGISTRO'],
        rowNum: 10, sortname: 'coa_mes', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblcostosadblue" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE COSTOS ADBLUE POR LITRO -', align: "center",
        colModel: [
            {name: 'coa_id', index: 'cap_id', align: 'left',width: 10, hidden:true},
            {name: 'coa_anio', index: 'coa_anio', align: 'center', width: 40},
            {name: 'coa_mes', index: 'coa_mes', align: 'center', width: 50},
            {name: 'coa_saldo', index: 'coa_saldo', align: 'center', width: 40},
            {name: 'coa_fecregistro', index: 'coa_fecregistro', align: 'center', width: 40}
        ],
        pager: '#paginador_tblcostos_adblue',
        rowList: [10, 20, 30, 40, 50],
        gridComplete: function () {
                var idarray = jQuery('#tblcostos_adblue').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                var firstid = jQuery('#tblcostos_adblue').jqGrid('getDataIDs')[0];
                    $("#tblcostos_adblue").setSelection(firstid);    
                }
            },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){$('#btn_modificar_costo_adblue').click();}
    });
    
});

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
                    url: 'costo_adblue/0?grid=costos_adblue'
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
                    url: 'costo_adblue/0?grid=costos_adblue'
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
    jQuery("#tblcostos_adblue").jqGrid('setGridParam', {
        url: 'costo_adblue/0?grid=costos_adblue'
    }).trigger('reloadGrid');
});

jQuery(document).on("change", "#cbx_coa_anio", function(){
    jQuery("#tblcostos_adblue").jqGrid('setGridParam', {
        url: 'costo_adblue/0?grid=costos_adblue&anio='+$(this).val()
    }).trigger('reloadGrid');
});