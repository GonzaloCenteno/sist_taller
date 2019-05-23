
function FormatoNumeros(cellValue, options, rowObject) {
    var color = (parseInt(cellValue) < 0) ? "red" : "black";
    var cellHtml = "<span style='color:" + color + "' originalValue='" +
                         cellValue + "'>" + cellValue + "</span>";
    return cellHtml;
}

jQuery(document).on("click", "#menu_push", function(){    
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse')) 
    {
        setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblcontrol_consumo').setGridWidth(width);
            $('#tblprom_gen_irizar').setGridWidth(width);
            $('#tblprom_gen_scania').setGridWidth(width);
            $('#tblprom_gen_rut').setGridWidth(width);
            
            $('#tblcost_opt_ruta').setGridWidth(width);
            $('#tblcost_gen_abast_ruta').setGridWidth(width);
            $('#tblcost_gen_abast_placa').setGridWidth(width);
        }, 300);
    }
    else
    {
       setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblcontrol_consumo').setGridWidth(width);
            $('#tblprom_gen_irizar').setGridWidth(width);
            $('#tblprom_gen_scania').setGridWidth(width);
            $('#tblprom_gen_rut').setGridWidth(width);
            
            $('#tblcost_opt_ruta').setGridWidth(width);
            $('#tblcost_gen_abast_ruta').setGridWidth(width);
            $('#tblcost_gen_abast_placa').setGridWidth(width);
       }, 300);
    } 
});

jQuery(document).on("click", "#btn_act_tblcontrol_consumo", function(){
    $("#txt_cde_placa").val('');
    jQuery("#tblcontrol_consumo").jqGrid('setGridParam', {
        url: 'control_consumo/0?grid=control_consumo&mes='+$("#cbx_cde_mes").val()+'&anio='+$("#cbx_cde_anio").val()
    }).trigger('reloadGrid');
});

function modificar_consumo(cde_id)
{
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'control_consumo/'+cde_id+'?show=consumo',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION');  
        },
        success: function(data) 
        {
            Estaciones = $('#ModalConsumoQabastecida').modal({backdrop: 'static', keyboard: false});
            Estaciones.find('.modal-title').text('EDITAR Q-ABASTECIDA');
            
            $("#lbl_cde_consumo").attr('cde_id',data[0].cde_id);
            $("#lbl_cde_consumo").html("ESTACION: " + "<b>"+data[0].est_descripcion+"</b>");
            $("#txt_cde_qabastecida").val(data[0].cde_qabastecida); 
            
            if (data[0].cde_qparcial === 1) 
            {
                $("#cabeceraConsumoQabastecida").show();
                $("#btn_actualizar_conest").attr('disabled',true);
                $("#txt_cde_qabastecida").attr('disabled',true);
            }
            else
            {
                $("#cabeceraConsumoQabastecida").hide();
                $("#btn_actualizar_conest").removeAttr('disabled');
                $("#txt_cde_qabastecida").removeAttr('disabled');
                setTimeout(function (){
                    $('#txt_cde_qabastecida').focus();
                }, 200);
            }
            swal.close();
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

jQuery(document).on("click", "#btn_actualizar_conest", function(){
    if ($('#txt_cde_consumo').val() == '') {
        mostraralertasconfoco('* EL CAMPO CONSUMO ES OBLIGATORIO...', '#txt_cde_consumo');
        return false;
    }
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'control_consumo/'+$("#lbl_cde_consumo").attr('cde_id')+'/edit',
        type: 'GET',
        data:
        {
            qabastecida:$('#txt_cde_qabastecida').val(),
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
                jQuery("#tblcontrol_consumo").jqGrid('setGridParam', {
                    url: 'control_consumo/0?grid=control_consumo&mes='+$("#cbx_cde_mes").val()+'&anio='+$("#cbx_cde_anio").val()
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal_1').click();
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

jQuery(document).on("change", "#cbx_cde_mes", function(){
    jQuery("#tblcontrol_consumo").jqGrid('setGridParam', {
        url: 'control_consumo/0?grid=control_consumo&mes='+$("#cbx_cde_mes").val()+'&anio='+$("#cbx_cde_anio").val()
    }).trigger('reloadGrid');
});

jQuery(document).on("click", "#btn_vw_buscar_placa", function(){
    buscar_placas();
});

function buscar_placas()
{
    jQuery("#tblcontrol_consumo").jqGrid('setGridParam', {
        url: 'control_consumo/0?grid=control_consumo&mes='+$("#cbx_cde_mes").val()+'&anio='+$("#cbx_cde_anio").val()+'&veh_placa='+$("#txt_cde_placa").val()
    }).trigger('reloadGrid');
}

function modificar_detalleconsumo()
{
    id = $('#tblcontrol_consumo').jqGrid ('getGridParam', 'selrow');
    if (id) 
    {
        cde_id = $('#tblcontrol_consumo').jqGrid ('getCell', id, 'xcde_id');
    
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'control_consumo/'+cde_id+'?show=consumodetalle',
            type: 'GET',
            beforeSend:function()
            {            
                MensajeEspera('RECUPERANDO INFORMACION');  
            },
            success: function(data) 
            {
                DetalleConsumo = $('#ModalConsumoDetalle').modal({backdrop: 'static', keyboard: false});
                DetalleConsumo.find('.modal-title').text('EDITAR DETALLE CONSUMO');
                DetalleConsumo.find('#btn_actualizar_condet').html('<i class="fa fa-pencil-square-o"></i> MODIFICAR').show();

                $("#lbl_cde_consumodet").attr('xcde_id',data[0].cde_id);
                $("#lbl_cde_consumodet").html("PLACA: " + "<b>"+data[0].veh_placa+"</b>");
                $("#txt_cde_condeseado").val(data[0].cde_condeseado); 
                $("#txt_cde_montoptimo").val(data[0].cde_montoptimo); 
                setTimeout(function (){
                    $('#txt_cde_condeseado').focus();
                }, 200);
                swal.close();
            },
            error: function(data) {
                MensajeAdvertencia("hubo un error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
            }
        });
    }
    else
    {
        mostraralertasconfoco('NO EXISTE UN DETALLE SELECCIONADO', '#tblcontrol_consumo');
    }
}

jQuery(document).on("click", "#btn_actualizar_condet", function(){
    if ($('#txt_cde_condeseado').val() == '') {
        mostraralertasconfoco('* EL CAMPO CONSUMO DESEADO ES OBLIGATORIO...', '#txt_cde_condeseado');
        return false;
    }
    if ($('#txt_cde_montoptimo').val() == '') {
        mostraralertasconfoco('* EL CAMPO MONTO OPTIMO ES OBLIGATORIO...', '#txt_cde_montoptimo');
        return false;
    }
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'control_consumo/'+$("#lbl_cde_consumodet").attr('xcde_id')+'/edit',
        type: 'GET',
        data:
        {
            cde_condeseado:$('#txt_cde_condeseado').val(),
            cde_montoptimo:$('#txt_cde_montoptimo').val(),
            tipo:2
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
                jQuery("#tblcontrol_consumo").jqGrid('setGridParam', {
                    url: 'control_consumo/0?grid=control_consumo&mes='+$("#cbx_cde_mes").val()+'&anio='+$("#cbx_cde_anio").val()
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal_2').click();
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

jQuery(document).on("click","#btn_vw_nuevos_condet", function(){
    modificar_detalleconsumo();
});

//Q-ABAST PARCIALES - RUTAS DE CONSUMOS

jQuery(document).on("click","#btn_modificar_txt_qparcial", function(){
    xcca_id = $('#tblcontrol_consumo').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo/'+$("#lbl_cde_consumo").attr('cde_id')+'?show=datos_qparcial',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION');  
        },
        success: function(data) 
        {
            html_detalle = "";
            $("#btn_cerrar_modal_1").click();
            ConsumoParcial = $('#ModalConsumoQparcial').modal({backdrop: 'static', keyboard: false});
            ConsumoParcial.find('.modal-title').text('VER Q-ABAST. PARCIALES');
            $("#lbl_conpar_placa").html("PLACA: " + "<b>"+$('#tblcontrol_consumo').jqGrid ('getCell', xcca_id, 'xcde_placa')+"</b>");
            $("#lbl_conpar_ruta").html("RUTA: " + "<b>"+$('#tblcontrol_consumo').jqGrid ('getCell', xcca_id, 'xcde_ruta')+"</b>");

            for(i=0;i<data.length;i++)
            {
                html_detalle = html_detalle+'<div class="col-md-12">'+
                                                '<div class="form-group">'+
                                                    '<label>Q-ABAST PARCIAL:</label>'+
                                                    '<div class="input-group">'+
                                                        '<div class="input-group-prepend">'+
                                                            '<span class="input-group-text"><i class="fa fa-tasks"></i></span>'+
                                                        '</div>'+
                                                        '<input type="hidden" name="contarqabastParcial[]"><input type="text" name="cdp_qparcial[]" value="'+data[i].cdp_qparcial+'" class="form-control text-center" maxlength="8" onkeypress="return soloNumeroTab(event);">'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>';
                $("#detalle_qabast_parcial").html(html_detalle);
            }
            swal.close();
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
});

jQuery(document).on("click", "#btn_actualizar_cqparciales", function(){
   
    var QabastPartmod = new FormData($("#FormularioQabastParcialMod")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo?tipo=3&cde_id='+$("#lbl_cde_consumo").attr('cde_id'),
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        data: QabastPartmod,
        success: function (data) 
        {
            if (data == 1) 
            {
                MensajeConfirmacion('EL RESPUESTA FUE ENVIADA CON EXITO');
                jQuery("#tblcontrol_consumo").jqGrid('setGridParam', {
                    url: 'control_consumo/0?grid=control_consumo&mes='+$("#cbx_cde_mes").val()+'&anio='+$("#cbx_cde_anio").val()
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal_3').click();
            }
            else
            {
                MensajeAdvertencia('NO SE PUDO ENVIAR LOS DATOS, OCURRIO UN PROBLEMA');
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

jQuery(document).on("change", "#cbx_dat_mes", function(){
    jQuery("#tblprom_gen_rut").jqGrid('setGridParam', {
        url: 'control_consumo/0?grid=prom_gen_rut&mes='+$("#cbx_dat_mes").val()+'&anio='+$("#cbx_dat_anio").val()
    }).trigger('reloadGrid');
    
    jQuery("#tblgen_scania").jqGrid('setGridParam', {
        url: 'control_consumo/0?grid=dat_gen_escania&mes='+$("#cbx_dat_mes").val()+'&anio='+$("#cbx_dat_anio").val()
    }).trigger('reloadGrid');
    
    jQuery("#tblprom_gen_irizar").jqGrid('setGridParam', {
        url: 'control_consumo/0?grid=dat_gen_irizar&mes='+$("#cbx_dat_mes").val()+'&anio='+$("#cbx_dat_anio").val()
    }).trigger('reloadGrid');
});

jQuery(document).on("click", "#nav-costos-tab", function(){
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'control_consumo/0?datos=traer_saldo',
        type: 'GET',
        data:
        {
            anio:$('#cbx_cost_anio').val(),
            mes:$('#cbx_cost_mes').val()
        },
        beforeSend:function()
        {            
            MensajeEspera('ENVIANDO INFORMACION');  
        },
        success: function(data) 
        {
            $("#txt_coa_saldo").html("<b> S/.  "+data[0].coa_saldo+"</b>");
            jQuery("#tblcost_opt_ruta").jqGrid('setGridParam', {
                url: 'control_consumo/0?grid=cost_opt_ruta&mes='+$("#cbx_cost_mes").val()+'&anio='+$("#cbx_cost_anio").val()
            }).trigger('reloadGrid');
            jQuery("#tblcost_gen_abast_ruta").jqGrid('setGridParam', {
                url: 'control_consumo/0?grid=cost_gen_abast_ruta&mes='+$("#cbx_cost_mes").val()+'&anio='+$("#cbx_cost_anio").val()
            }).trigger('reloadGrid');
            jQuery("#tblcost_gen_abast_placa").jqGrid('setGridParam', {
                url: 'control_consumo/0?grid=cost_gen_abast_placa&mes='+$("#cbx_cost_mes").val()+'&anio='+$("#cbx_cost_anio").val()
            }).trigger('reloadGrid');
            swal.close();
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
});

jQuery(document).on("change","#cbx_cost_mes", function(){ 
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'control_consumo/0?datos=traer_saldo',
        type: 'GET',
        data:
        {
            anio:$('#cbx_cost_anio').val(),
            mes:$('#cbx_cost_mes').val()
        },
        beforeSend:function()
        {            
            MensajeEspera('ENVIANDO INFORMACION');  
        },
        success: function(data) 
        {
            if (data == 0) 
            {
                MensajeAdvertencia('NO EXISTEN DATOS CON ESTOS PARAMETROS');
                $("#txt_coa_saldo").html("<b> S/. 0.000</b>");
                $("#total_cdg").val("S/.0.000");
                $("#total_cra").val("S/.0.000");
                $("#total_cae").val("S/.0.000");
                
                $("#total_cta").val("S/.0.000");
                $("#total_cte").val("S/.0.000");
                $("#total_ctae").val("S/.0.000");
                
                $("#total_cta_placa").val("S/.0.000");
                $("#total_cte_placa").val("S/.0.000");
                $("#total_ctae_placa").val("S/.0.000");
                jQuery("#tblcost_opt_ruta").jqGrid("clearGridData");
                jQuery("#tblcost_gen_abast_ruta").jqGrid("clearGridData");
                jQuery("#tblcost_gen_abast_placa").jqGrid("clearGridData");
                console.log(data);
            }
            else
            {
                $("#txt_coa_saldo").html("<b> S/.  "+data[0].coa_saldo+"</b>");
                jQuery("#tblcost_opt_ruta").jqGrid('setGridParam', {
                    url: 'control_consumo/0?grid=cost_opt_ruta&mes='+$("#cbx_cost_mes").val()+'&anio='+$("#cbx_cost_anio").val()
                }).trigger('reloadGrid');
                jQuery("#tblcost_gen_abast_ruta").jqGrid('setGridParam', {
                    url: 'control_consumo/0?grid=cost_gen_abast_ruta&mes='+$("#cbx_cost_mes").val()+'&anio='+$("#cbx_cost_anio").val()
                }).trigger('reloadGrid');
                jQuery("#tblcost_gen_abast_placa").jqGrid('setGridParam', {
                    url: 'control_consumo/0?grid=cost_gen_abast_placa&mes='+$("#cbx_cost_mes").val()+'&anio='+$("#cbx_cost_anio").val()
                }).trigger('reloadGrid');
                swal.close();
            }
            
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
});

function traer_datos_desplegable(tabla,numero)
{
    console.log(tabla.id);
    var titulos = jQuery("#"+tabla.id).jqGrid ('getGridParam', 'colNames');
    var columnas = jQuery("#"+tabla.id).jqGrid ('getGridParam', 'colModel');
    var tipo = numero;
    //console.log(otro[1].index);
    Reportes = $('#ModalReportesGeneral').modal({backdrop: 'static', keyboard: false});
    Reportes.find('.modal-title').text('REPORTES GENERALES');
    $("#ModalReportesGeneralFooter").html('<button type="button" onClick="abrir_reportes_generales('+tipo+')" class="btn btn-primary btn-xl"><i class="fa fa-sign-in"></i> ABRIR REPORTE</button><button type="button" id="btn_cerrar_modal_4" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>');
    html = '';
    for(i=1;i < columnas.length; i++)
    {
        //console.log("titulos: " + columnas[i].index + " columnas: " + titulos[i]);
        html = html + '<option value='+columnas[i].index+'> ..:: '+titulos[i]+' ::.. </option>';
    }
    $("#cbx_rep_columna").html(html);
}

function abrir_reportes_generales(tipo)
{
    if (tipo === 1 || tipo === 2 || tipo === 3) 
    {
        window.open('control_consumo/0?reportes=rep_generales&columna='+$("#cbx_rep_columna").val()+'&orden='+$("#cbx_rep_orden").val()+'&tipo='+tipo+'&anio='+$("#cbx_dat_anio").val()+'&mes='+$("#cbx_dat_mes").val());
    }
    else
    {
        window.open('control_consumo/0?reportes=rep_generales&columna='+$("#cbx_rep_columna").val()+'&orden='+$("#cbx_rep_orden").val()+'&tipo='+tipo+'&anio='+$("#cbx_cost_anio").val()+'&mes='+$("#cbx_cost_mes").val());
    }
}

jQuery(document).on("click","#btn_imprimir_informacion_excel",function(){
    window.open('control_consumo/0?reportes=reporte_informacion_excel&anio='+$("#cbx_cost_anio").val()+'&mes='+$("#cbx_cost_mes").val());
});