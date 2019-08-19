
jQuery(document).on("click", "#menu_push", function(){    
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse')) 
    {
        setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblvaledetalle').setGridWidth(width);
        }, 300);
    }
    else
    {
       setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblvaledetalle').setGridWidth(width);
       }, 300);
    } 
});

$("#txt_veh_placa").keyup(function(){
    if ($(this).val().length >= 3) 
    {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            url: 'almacen_combustible/0?busqueda=vehiculos&datos='+$(this).val(),
            success: function (data) {
                var $datos = data;
                $("#txt_veh_placa").autocomplete({
                    source: $datos,
                    focus: function (event, ui) {
                        $("#hiddentxt_veh_placa").val(ui.item.value);
                        return false;
                    },
                    select: function (event, ui) {
                        $("#hiddentxt_veh_placa").val(ui.item.value);
                        $("#txt_veh_placa").val(ui.item.label);
                        $("#txt_veh_descripcion").val(ui.item.veh_vehiculo);
                        return false;
                    }
                });
            }
        });
    }
});
        
$("#txt_tri_nrodoc").keyup(function(){
    if ($(this).val().length >= 3) 
    {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            url: 'almacen_combustible/0?busqueda=trp_nrodoc&nro_doc='+$(this).val(),
            success: function (data) {
                var $datos = data;
                $("#txt_tri_nrodoc").autocomplete({
                    source: $datos,
                    focus: function (event, ui) {
                        $("#hiddentxt_tri_nrodoc").val(ui.item.value);
                        return false;
                    },
                    select: function (event, ui) {
                        $("#hiddentxt_tri_nrodoc").val(ui.item.value);
                        $("#txt_tri_nrodoc").val(ui.item.label);
                        $("#txt_tri_nombres").val(ui.item.tripulante);
                        return false;
                    }
                });
            }
        });
    }
    if ($(this).val().length == 0) 
    {
        $("#hiddentxt_tri_nrodoc").val('');
    }
});
        
$("#txt_tri_nombres").keyup(function(){
    if ($(this).val().length >= 3) 
    {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            url: 'almacen_combustible/0?busqueda=trp_nombres&nombres='+$(this).val(),
            success: function (data) {
                var $datos = data;
                $("#txt_tri_nombres").autocomplete({
                    source: $datos,
                    focus: function (event, ui) {
                        $("#hiddentxt_tri_nrodoc").val(ui.item.value);
                        return false;
                    },
                    select: function (event, ui) {
                        $("#hiddentxt_tri_nrodoc").val(ui.item.value);
                        $("#txt_tri_nombres").val(ui.item.label);
                        $("#txt_tri_nrodoc").val(ui.item.documento);
                        return false;
                    },
                });
            }
        });
    }
    if ($(this).val().length == 0) 
    {
        $("#hiddentxt_tri_nrodoc").val('');
    }
});

$("#txt_vca_numvale").keypress(function (e) {
    if (e.which == 13) {
        buscar_nrovale();
    }
});

function buscar_nrovale()
{
    if ($('#txt_vca_numvale').val() == '') {
        mostraralertasconfoco('* EL CAMPO NUMERO VALE NO PUEDE ESTAR EN BLANCO...', '#txt_vca_numvale');
        return false;
    }
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'almacen_combustible/0?datos=datos_nrovale',
        type: 'GET',
        data:
        {
            nrovale:$("#txt_vca_numvale").val(),
        },
        beforeSend:function()
        {        
            $('#card_principal').block({ 
                message: '<h1><img src="img/img_cromotex/cargador_2.gif" />RECUPERANDO INFORMACION</h1>', 
                css: { border: '5px solid #a00',width: '350px' } 
            });
        },
        success: function(data) 
        {
            let estado;
            if (data.msg == 1) 
            {
                $("#vca_id").val(data.vca_id);
                $("#txt_cntmtri").val(data.vale_cntmtri);
                $("#txt_cntmtrf").val(data.vale_cntmtrf);
                $("#txt_kilometraje_ant").val(data.vale_klmtrj);
                $("#hiddentxt_veh_placa").val(data.veh_id);
                $("#txt_veh_placa").val(data.veh_placa);
                $("#txt_veh_descripcion").val(data.vehiculo);
                $("#hiddentxt_tri_nrodoc").val(data.tri_id);
                $("#txt_tri_nrodoc").val(data.dni);
                $("#txt_tri_nombres").val(data.tripulante);
                $("#txt_referencia").val(data.vale_referencia);
                $("#txt_vca_fecemision").val(data.vale_fecemision);
                $("#cbx_ruta_area").val(data.est_id)
                
                if (data.vale_estado == 0) 
                {
                    estado = 'Anulado';
                }
                else if(data.vale_estado == 1)
                {
                    estado = 'Ingresado';
                }
                else
                {
                    estado = 'Confirmado';
                }
                $("#txt_estado").val(estado);
                
                if (data.vale_bomba == '') 
                {
                    $("#cbx_bomba").val('0');
                }
                else
                {
                    $("#cbx_bomba").val(data.vale_bomba);
                }
                
                $("#btn_guardar").attr('disabled',true);
                buscar_nrovale_detalle(data.vale_numvale);
            }
            else
            {
                mostraralertasconfoco('NO SE ENCONTRO DATOS PARA ESTE N° DE VALE');
                $("#txt_vca_numvale").val('');
                $("#txt_referencia").val('');
                $("#txt_tri_nrodoc").val('');
                $("#txt_veh_placa").val('');
                $("#txt_veh_descripcion").val('');
                $("#hiddentxt_tri_nrodoc").val('');
                $("#txt_tri_nombres").val('');
                $("#txt_kilometraje").val('');
                $("#cbx_bomba").val(0);
                $("#cbx_ruta_area").val(0);
                $("#txt_cntmtri").val('');
                $("#txt_cntmtrf").val('');
                $("#txt_kilometraje_ant").val('');
                $("#txt_estado").val('');
                $("#trh_ntransaccion").val(0);
                $("#vca_id").val(0);
                $("#txt_vca_fecemision").datepicker({ dateFormat: "dd/mm/yy", minDate: -5}).datepicker("setDate", new Date());
                jQuery("#tblvaledetalle").jqGrid("clearGridData");
                $("#txt_vca_numvale").focus();
            }
            $('#card_principal').unblock();
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            $('#card_principal').unblock();
            console.log('error');
            console.log(data);
        }
    });
}

function buscar_nrovale_detalle(vale_numvale)
{
    jQuery("#tblvaledetalle").jqGrid('setGridParam', {
        url: 'almacen_combustible/0?tabla=tblvaledetalle_vde&vale_numvale='+vale_numvale
    }).trigger('reloadGrid'); 
}

//if(data !== '0')
//{
//    for(i=0;i<data.length;i++)
//    {
//        for(j=0;j<data[i].length;j++)
//        {
//            console.log(data[i][j]); 
//        }
//    }
//}

jQuery(document).on("click", "#btn_conectar_grifo", function(){
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'almacen_combustible/0?datos=datos_topkat',
        type: 'GET',
        beforeSend:function()
        {            
            $('#card_principal').block({ 
                message: '<h1><img src="img/img_cromotex/cargador_2.gif" />EXTRAYENDO INFORMACION DE GRIFO</h1>', 
                css: { border: '5px solid #a00',width: '350px' } 
            });
        },
        success: function(data) 
        {
            html = '';
            if(data.msg === 0)
            {
                mostraralertasconfoco('NO SE ENCONTRARON NUEVOS INGRESOS');
                $("#txt_vca_numvale").val('');
                $("#txt_referencia").val('');
                $("#txt_tri_nrodoc").val('');
                $("#txt_veh_placa").val('');
                $("#txt_veh_descripcion").val('');
                $("#hiddentxt_tri_nrodoc").val('');
                $("#txt_tri_nombres").val('');
                $("#txt_kilometraje").val('');
                $("#cbx_bomba").val(0);
                $("#cbx_ruta_area").val(0);
                $("#txt_cntmtri").val('');
                $("#txt_cntmtrf").val('');
                $("#txt_kilometraje_ant").val('');
                $("#trh_ntransaccion").val(0);
                $("#vca_id").val(0);
                $("#txt_estado").val('');
                $("#txt_vca_fecemision").datepicker({ dateFormat: "dd/mm/yy", minDate: -5}).datepicker("setDate", new Date());
                jQuery("#tblvaledetalle").jqGrid("clearGridData");
            }
            else if(data.msg === 1)
            {
                mostraralertasconfoco('LA PLACA DEL VEHICULO NO SE ENCUENTRA REGISTRADA');
                console.log(data);
            }
            else
            {
                $.confirm({
                    icon:'fa fa-tasks',
                    title: 'SELECCIONAR SISTEMAS',
                    type: 'red',
                    animationBounce: 2,
                    typeAnimated: true,
                    backgroundDismiss: false,
                    backgroundDismissAnimation: 'glow',
                    columnClass: 'col-md-8',
                    closeIcon: true,
                    closeIconClass: 'fa fa-close',
                    theme:'material',
                    buttons: {
                        CERRAR: {
                            text: 'CERRAR VENTANA',
                            btnClass: 'btn-danger btn-lg btn-rounded',
                            action: function(){}
                        }
                    },
                    content:'<div class="col-md-12 tblAsignarSistemas">'+
                                '<div class="form-group">'+
                                    '<table class="table table-sm table-bordered text-center table-striped">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>'+
                                                '<th>PLACA</th>'+
                                                '<th>MARCA</th>'+
                                                '<th>CLASE</th>'+
                                                '<th>SELEC.</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody id="detalle">'+
                                        '</tbody>'+
                                    '</table>'+                                                                
                                '</div>'+
                            '</div>',
                    onContentReady: function () {
                        //console.log(data);
                        for(i=0;i<data.data.length;i++)
                        {
                            html = html+'<tr><th>'+(i+1)+'</th>\n\
                                <td>'+data.data[i].veh_placa+'</td>\n\
                                <td>'+data.data[i].veh_marca+'</td>\n\
                                <td>'+data.data[i].veh_clase+'</td>\n\
                                <td><button class="btn btn-block btn-danger btn-sm" onclick="fn_seleccionar_placa('+data.data[i].veh_id+',\''+data.data[i].veh_placa+'\','+data.id_trans+')"><i class="fa fa-plus-square fa-2x" style="width:100%"></i></button></td></tr>';
                            $("#detalle").html(html);
                        }
                    }
                });   
            }
            $('#card_principal').unblock();
        },
        error: function(data) {
            MensajeAdvertencia("HUBO UN ERROR, COMUNICAR CON EL ADMINISTRADOR");
            $('#card_principal').unblock();
            console.log('error');
            console.log(data);
        }
    });
})

jQuery(document).on("click", "#btn_deshacer", function(){
    $("#txt_vca_numvale").val('');
    $("#txt_referencia").val('');
    $("#txt_tri_nrodoc").val('');
    $("#txt_veh_placa").val('');
    $("#txt_veh_descripcion").val('');
    $("#hiddentxt_tri_nrodoc").val('');
    $("#txt_tri_nombres").val('');
    $("#txt_kilometraje").val('');
    $("#cbx_bomba").val(0);
    $("#cbx_ruta_area").val(0);
    $("#txt_cntmtri").val('');
    $("#txt_cntmtrf").val('');
    $("#txt_kilometraje_ant").val('');
    $("#txt_estado").val('');
    $("#trh_ntransaccion").val(0);
    $("#vca_id").val(0);
    jQuery("#tblvaledetalle").jqGrid("clearGridData");
    $("#txt_vca_numvale").focus();
    $("#txt_vca_fecemision").datepicker({ dateFormat: "dd/mm/yy", minDate: -5}).datepicker("setDate", new Date());
    $("#btn_guardar").removeAttr('disabled');
});

function fn_seleccionar_placa(veh_id,veh_placa,id_trans)
{
    //$('.jconfirm-buttons').children('button').click();
    //console.log(veh_id,veh_placa);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'almacen_combustible/0?seleccionar=datos_placa',
        type: 'GET',
        data:
        {
            veh_id:veh_id,
            veh_placa:veh_placa,
            id_trans:id_trans
        },
        beforeSend:function()
        {            
            $('.jconfirm-buttons').children('button').click();
            $('#card_principal').block({ 
                message: '<h1><img src="img/img_cromotex/cargador_2.gif" />RECUPERANDO INFORMACION</h1>', 
                css: { border: '5px solid #a00',width: '350px' } 
            });
        },
        success: function(data) 
        {
            if (data === '0') 
            {
                MensajeAdvertencia('NO SE ENCONTRARON REGISTROS ANTERIORES PARA ESTA PLACA');
                $("#txt_vca_numvale").val('');
                $("#txt_referencia").val('');
                $("#txt_tri_nrodoc").val('');
                $("#txt_veh_placa").val('');
                $("#txt_veh_descripcion").val('');
                $("#hiddentxt_tri_nrodoc").val('');
                $("#txt_tri_nombres").val('');
                $("#txt_kilometraje").val('');
                $("#cbx_bomba").val(0);
                $("#cbx_ruta_area").val(0);
                $("#txt_cntmtri").val('');
                $("#txt_cntmtrf").val('');
                $("#txt_kilometraje_ant").val('');
                $("#txt_estado").val('');
                $("#trh_ntransaccion").val(0);
                $("#vca_id").val(0);
                $("#txt_vca_fecemision").datepicker({ dateFormat: "dd/mm/yy", minDate: -5}).datepicker("setDate", new Date());
                jQuery("#tblvaledetalle").jqGrid("clearGridData");
            }
            else
            {
                let estado;
            
                $("#txt_vca_numvale").val(data.vale);
                $("#txt_referencia").val(data.referencia);
                $("#hiddentxt_veh_placa").val(data.veh_id);
                $("#txt_veh_placa").val(data.veh_placa);
                $("#txt_veh_descripcion").val(data.veh_vehiculo);
                $("#cbx_bomba").val(data.vca_bomba);
                $("#cbx_ruta_area").val(0);
                $("#txt_cntmtri").val(data.vale_cntmtri);
                $("#txt_cntmtrf").val(data.vale_cntmtrf);
                $("#txt_kilometraje_ant").val(data.kilometraje);

                if (data.vale_estado == 0) 
                {
                    estado = 'Anulado';
                }
                else if(data.vale_estado == 1)
                {
                    estado = 'Ingresado';
                }
                else
                {
                    estado = 'Confirmado';
                }
                $("#txt_estado").val(estado);
                $("#trh_ntransaccion").val(data.id_trans);

                jQuery("#tblvaledetalle").jqGrid('setGridParam', {
                    url: 'almacen_combustible/0?tabla=tbltransaccioneshistoricas_trh&id_trans='+data.id_trans
                }).trigger('reloadGrid'); 
            }
            $('#card_principal').unblock();
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            $('#card_principal').unblock();
            console.log('error');
            console.log(data);
        }
    });
}

jQuery(document).on("click", "#btn_guardar", function(){
    if ($('#txt_vca_numvale').val() == '') {
        mostraralertasconfoco('* EL CAMPO NUMERO VALE NO PUEDE ESTAR EN BLANCO...', '#txt_vca_numvale');
        return false;
    }
    
    if ($('#txt_estado').val() == '') {
        mostraralertasconfoco('* EL CAMPO ESTADO ES OBLIGATORIO...', '#txt_estado');
        return false;
    }
    
    if ($('#hiddentxt_tri_nrodoc').val() == '') {
        mostraralertasconfoco('* EL CAMPO DNI TRABAJADOR ES OBLIGATORIO...', '#txt_tri_nrodoc');
        return false;
    }
    
    if ($('#hiddentxt_veh_placa').val() == '') {
        mostraralertasconfoco('* EL CAMPO PLACA ES OBLIGATORIO...', '#txt_veh_placa');
        return false;
    }
    
    if ($('#txt_cntmtri').val() == '') {
        mostraralertasconfoco('* EL CAMPO CONTOMETRAJE INICIAL ES OBLIGATORIO...', '#txt_cntmtri');
        return false;
    }
    
    if ($('#txt_cntmtrf').val() == '') {
        mostraralertasconfoco('* EL CAMPO CONTOMETRAJE FINAL ES OBLIGATORIO...', '#txt_cntmtrf');
        return false;
    }
    
    if ($('#txt_kilometraje_ant').val() == '') {
        mostraralertasconfoco('* EL CAMPO KILOMETRAJE ANTERIOR ES OBLIGATORIO...', '#txt_kilometraje_ant');
        return false;
    }
    
    if ($('#txt_kilometraje').val() == '') {
        mostraralertasconfoco('* EL CAMPO KILOMETRAJE ES OBLIGATORIO...', '#txt_kilometraje');
        return false;
    }
    
    if ($('#cbx_bomba').val() == '0') {
        mostraralertasconfoco('* SE DEBE SELECCIONAR UNA BOMBA...', '#cbx_bomba');
        return false;
    }
    
    if ($('#cbx_ruta_area').val() == '0') {
        mostraralertasconfoco('* SE DEBE SELECCIONAR UNA RUTA...', '#cbx_ruta_area');
        return false;
    }
    
    if($("#tblvaledetalle").getGridParam("reccount") == 0){
        mostraralertasconfoco('* DEBE EXISTIR UN DETALLE PARA EL VALE...', '#txt_vca_numvale');
        return false;
    }
    
    var datos_combustible = new FormData($("#FormularioCombustible")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'almacen_combustible?cantidad='+jQuery("#tblvaledetalle").jqGrid('getRowData')[0].cantidad+'&id_trans='+$("#trh_ntransaccion").val()+'&tipo=1',
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        data:datos_combustible,
        beforeSend:function()
        {            
            $('#card_principal').block({ 
                message: '<h1><img src="img/img_cromotex/cargador_2.gif" />RECUPERANDO INFORMACION</h1>', 
                css: { border: '5px solid #a00',width: '350px' } 
            });
        },
        success: function (data) 
        {
            if (data.msg == 1) 
            {
                $('#card_principal').unblock();
                MensajeConfirmacion('SE GUARDO LA INFORMACION CON EXITO');
                $("#btn_deshacer").click();
                ver_vale(data.vca_id);
                
                config = qz.configs.create("KONICA MINOLTA bizhub 558e PCL (10.1.4.234) UPD");

                var data = [{
                    type: 'pdf',
                    format: 'base64',
                    data: data.pdf
                }];
                qz.print(config, data).catch(function(e) {
                    var varPPR = "" + e;
                    varPPR = varPPR.replace("Cannot find printer with name", "No se encontro la impresora con el nombre");
                    varPPR = varPPR.replace("Printer is not accepting job", "La impresora no esta conectada");
                    FunOpenMenssageError("" + varPPR);
                    console.error(e);
                }); 
            }
            else
            {
                MensajeAdvertencia('OCURRIO UN PROBLEMA AL ENVIAR EL FORMULARIO');
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

function ver_vale(vca_id)
{
    Vale = $('#ModalValeImpresion').modal({backdrop: 'static', keyboard: false});
    Vale.find('.modal-title').text('IMPRESION DE VALE');
    $('#iframe_vale').attr('src','almacen_combustible/'+vca_id+'?show=imprimir_vale');
}

jQuery(document).on("click", "#btn_imprimir", function(){
    if ($("#vca_id").val() == 0) 
    {
        mostraralertasconfoco('* DEBES SELECCIONAR UN VALE...', '#txt_vca_numvale');
        return false;
    }
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: 'almacen_combustible/'+$("#vca_id").val()+'?show=verificar_vale',
        success: function(data) {
            //console.log(data);
            if (data.msg == 1) 
            {
                ver_vale($("#vca_id").val());
                config = qz.configs.create("KONICA MINOLTA bizhub 558e PCL (10.1.4.234) UPD");

                var data = [{
                    type: 'pdf',
                    format: 'base64',
                    data: data.ruta
                }];
                qz.print(config, data).catch(function(e) {
                    var varPPR = "" + e;
                    varPPR = varPPR.replace("Cannot find printer with name", "No se encontro la impresora con el nombre");
                    varPPR = varPPR.replace("Printer is not accepting job", "La impresora no esta conectada");
                    FunOpenMenssageError("" + varPPR);
                    console.error(e);
                });  
            }
            else
            {
                MensajeAdvertencia('OCURRIO UN PROBLEMA AL INTENTAR IMPRIMIR EL VALE, VALE NO ENCONTRADO');
                console.log(data);
            }                                                                 
        },
        error: function(data) {
            MensajeAdvertencia("OCURRIO UN ERROR, COMUNICARSE CON EL ADMINISTRADOR");
            console.log('error');
            console.log(data);
        }
    });
});

jQuery(document).on("click", "#btn_editar", function() {
    if ($('#vca_id').val() == 0) {
        mostraralertasconfoco('* DEBES SELECCIONAR UN VALE...', '#txt_vca_numvale');
        return false;
    }
    
    if ($('#txt_estado').val() == '') {
        mostraralertasconfoco('* EL CAMPO ESTADO ES OBLIGATORIO...', '#txt_estado');
        return false;
    }
    
    if ($('#hiddentxt_tri_nrodoc').val() == '') {
        mostraralertasconfoco('* EL CAMPO DNI TRABAJADOR ES OBLIGATORIO...', '#txt_tri_nrodoc');
        return false;
    }
    
    if ($('#hiddentxt_veh_placa').val() == '') {
        mostraralertasconfoco('* EL CAMPO PLACA ES OBLIGATORIO...', '#txt_veh_placa');
        return false;
    }
    
    if ($('#txt_cntmtri').val() == '') {
        mostraralertasconfoco('* EL CAMPO CONTOMETRAJE INICIAL ES OBLIGATORIO...', '#txt_cntmtri');
        return false;
    }
    
    if ($('#txt_cntmtrf').val() == '') {
        mostraralertasconfoco('* EL CAMPO CONTOMETRAJE FINAL ES OBLIGATORIO...', '#txt_cntmtrf');
        return false;
    }
    
    if ($('#txt_kilometraje_ant').val() == '') {
        mostraralertasconfoco('* EL CAMPO KILOMETRAJE ANTERIOR ES OBLIGATORIO...', '#txt_kilometraje_ant');
        return false;
    }
    
    if ($('#txt_kilometraje').val() == '') {
        mostraralertasconfoco('* EL CAMPO KILOMETRAJE ES OBLIGATORIO...', '#txt_kilometraje');
        return false;
    }
    
    if ($('#cbx_bomba').val() == '0') {
        mostraralertasconfoco('* SE DEBE SELECCIONAR UNA BOMBA...', '#cbx_bomba');
        return false;
    }
    
    if ($('#cbx_ruta_area').val() == '0') {
        mostraralertasconfoco('* SE DEBE SELECCIONAR UNA RUTA...', '#cbx_ruta_area');
        return false;
    }
    
    if($("#tblvaledetalle").getGridParam("reccount") == 0){
        mostraralertasconfoco('* DEBE EXISTIR UN DETALLE PARA EL VALE...', '#txt_vca_numvale');
        return false;
    }
    
    var datos_combustible = new FormData($("#FormularioCombustible")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'almacen_combustible?tipo=2',
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        data:datos_combustible,
        beforeSend:function()
        {            
            $('#card_principal').block({ 
                message: '<h1><img src="img/img_cromotex/cargador_2.gif" />MODIFICANDO INFORMACION</h1>', 
                css: { border: '5px solid #a00',width: '350px' } 
            });
        },
        success: function (data) 
        {
            if (data.msg == 1) 
            {
                $('#card_principal').unblock();
                MensajeConfirmacion('SE MODIFICO LA INFORMACION CON EXITO');
                $("#btn_deshacer").click();
                ver_vale(data.vca_id);
                
                config = qz.configs.create("KONICA MINOLTA bizhub 558e PCL (10.1.4.234) UPD");

                var data = [{
                    type: 'pdf',
                    format: 'base64',
                    data: data.pdf
                }];
                qz.print(config, data).catch(function(e) {
                    var varPPR = "" + e;
                    varPPR = varPPR.replace("Cannot find printer with name", "No se encontro la impresora con el nombre");
                    varPPR = varPPR.replace("Printer is not accepting job", "La impresora no esta conectada");
                    FunOpenMenssageError("" + varPPR);
                    console.error(e);
                }); 
            }
            else
            {
                MensajeAdvertencia('OCURRIO UN PROBLEMA AL MODIFICAR EL FORMULARIO DE VALE');
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