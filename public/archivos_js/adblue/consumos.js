
jQuery(document).on("click", "#menu_push", function(){    
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse')) 
    {
        setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblconsumosdet').setGridWidth(width);
        }, 300);
    }
    else
    {
       setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblconsumosdet').setGridWidth(width);
       }, 300);
    } 
});

jQuery(document).on("click", "#btn_vw_consumocab", function(){
    $.ajax({
        url: 'consumo/0?datos=traer_estaciones',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('CARGANDO INFORMACION');  
        },
        success: function(data) 
        {
            html="";
  
            for(i=0;i<data.length;i++)
            {
                html = html+'<option value='+data[i].rut_id+'>'+data[i].estaciones+'</option>';
            }
           
            $("#cbx_consumo_ruta").html(html);
            $("#cbx_consumo_ruta").change();
            swal.close();
            mostrarformulario(true);
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
});

function mostrarformulario(flag)
{
    if (flag)
    {
        $("#listadoRegistros").hide();
        $("#formularioRegistros").show();
        $("#listadoButtons").hide();
        $("#formularioButtons").show();
        $("#form_codigo").focus();
        $("#cabecera_consumo").hide();
    }
    else
    {
        $("#listadoRegistros").show();
        $("#formularioRegistros").hide();
        $("#listadoButtons").show();
        $("#formularioButtons").hide();
        $("#cbx_consumo_ruta").removeAttr('disabled');
        $("#btn_generar_consumodet").removeAttr('disabled');
        $("#btn_vw_consumoscab_Guardar").attr('disabled',true);
        $("#btn_vw_otrosconsumos_Guardar").hide();
        $("#cabecera_consumo").show();
    }
}

jQuery(document).on("click", "#btn_vw_consumoscab_Cancelar", function(){
    mostrarformulario(false);
    $(".filas_consumocab").remove();
});

function autocompletar_personas(textbox){
    $.ajax({
        type: 'GET',
        url: 'consumo/0?busqueda=personas',
        success: function (data) {
            var $datos = data;
            $("#" + textbox).autocomplete({
                source: $datos,
                minLength: 3,
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

var cont=0;
var detalles=0;
jQuery(document).on("click", "#btn_generar_consumodet", function(){
    rut_id = $("#cbx_consumo_ruta").val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo/'+rut_id+'?show=generar_consumos',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION');  
        },
        success: function(data) 
        {
            var num = 0;
            html="";
            
            if (data == "") 
            {
                mostraralertasconfoco('LA RUTA FUE INACTIVADA');
            }
            else
            {
                if (data[0].rut_descripcion == 'OTR') 
                {
                    $("#btn_vw_consumoscab_Guardar").attr('disabled',true);
                    $(".filas_consumocab").remove();
                    $("#btn_generar_consumodet").attr('disabled',true);
                    $("#cbx_consumo_ruta").attr('disabled',true);

                    html = '<div class="form-row">\n\
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">\n\
                                    <table id="consumodet" class="table table-striped table-bordered table-condensed table-hover">\n\
                                       <thead style="background-color:#A9D0F5">\n\
                                        <th style="width: 5%;"></th>\n\
                                        <th style="width: 10%;">FECHA</th>\n\
                                        <th style="width: 5%;">ESTACION</th>\n\
                                        <th style="width: 15%;">CONDUCTOR</th>\n\
                                        <th style="width: 15%;">PILOTO</th>\n\
                                        <th style="width: 10%;">KM</th>\n\
                                        <th style="width: 10%;">%STOP EN TANQUE</th>\n\
                                        <th style="width: 10%;">Q-ABAST.</th>\n\
                                        <th style="width: 15%;">OBSERVACIONES</th>\n\
                                        <th style="width: 8%;">INGRESO</th>\n\
                                        <th style="width: 10%;">SALIDA</th>\n\
                                        <th style="width: 10%;">STOP</th>\n\
                                        </thead>\n\
                                        <tbody id="cuerpodet">\n\
                                            <tr class="filas_consumocab" id="filas_consumocab">\n\
                                                <td><button type="button" id="agregar_otrasrutas" class="btn btn-success"><i class="fa fa-plus-square"></i></button></td>\n\
                                                <td><input type="text" name="fecha[]" class="form-control text-uppercase text-center otro" readonly="readonly"></td>\n\
                                                <td><input type="hidden" name="contador[]"><input type="hidden" name="estacion[]" id="hiddenestacion_'+num+'"><input type="text" id="estacion_'+num+'" class="form-control text-uppercase text-center"></td>\n\
                                                <td><input type="hidden" name="conductor[]" id="hiddenconductor_'+num+'"><input type="text" id="conductor_'+num+'" class="form-control conductor text-uppercase text-center"></td>\n\
                                                <td><input type="hidden" name="piloto[]" id="hiddenpiloto_'+num+'"><input type="text" id="piloto_'+num+'" class="form-control piloto text-uppercase text-center"></td>\n\
                                                <td><input type="text" name="km[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="6" placeholder="0"></td>\n\
                                                <td><input type="text" name="xtanque[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0"></td>\n\
                                                <td><input type="text" name="qabast[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"></td>\n\
                                                <td><input type="text" name="observ[]" class="form-control text-uppercase text-center" maxlength="255"></td>\n\
                                                <td><input type="text" name="ingreso[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"></td>\n\
                                                <td><input type="text" name="salida[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"></td>\n\
                                                <td><input type="text" name="stop[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"></td>\n\
                                            </tr>\n\
                                        </tbody>\n\
                                    </table>\n\
                                </div>\n\
                            </div>';
                    autocompletar_estaciones('estacion_'+num);
                    autocompletar_personas('conductor_'+num);
                    autocompletar_personas('piloto_'+num);
                    $("#consumodet").html(html);
                    cont++;
                    detalles=detalles+1;
                    evaluar();
                    swal.close();
                }
                else
                {
                    html = '<div class="form-row">\n\
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">\n\
                                    <table id="consumodet" class="table table-striped table-bordered table-condensed table-hover">\n\
                                       <thead style="background-color:#A9D0F5">\n\
                                        <th style="width: 10%;">FECHA</th>\n\
                                        <th style="width: 5%;">ESTACION</th>\n\
                                        <th style="width: 15%;">CONDUCTOR</th>\n\
                                        <th style="width: 15%;">PILOTO</th>\n\
                                        <th style="width: 10%;">KM</th>\n\
                                        <th style="width: 10%;">%STOP EN TANQUE</th>\n\
                                        <th style="width: 10%;">Q-ABAST.</th>\n\
                                        <th style="width: 15%;">OBSERVACIONES</th>\n\
                                        <th style="width: 8%;">INGRESO</th>\n\
                                        <th style="width: 10%;">SALIDA</th>\n\
                                        <th style="width: 10%;">STOP</th>\n\
                                        </thead>\n\
                                    </table>\n\
                                </div>\n\
                            </div>';
                    for(i=1;i<data.length;i++)
                    {
                        num++;
                        html = html+'<tr class="filas_consumocab" id="filas_consumocab">\n\
                            <td><input type="text" name="fecha[]" class="form-control text-uppercase text-center otro" readonly="readonly"></td>\n\
                            <td><input type="hidden" name="contador[]"><input type="hidden" name="estacion[]" value="'+data[i].est_id+'"><label>'+data[i].est_descripcion+'</label></td>\n\
                            <td><input type="hidden" name="conductor[]" id="hiddenconductor_'+num+'"><input type="text" id="conductor_'+num+'" class="form-control conductor text-uppercase text-center"></td>\n\
                            <td><input type="hidden" name="piloto[]" id="hiddenpiloto_'+num+'"><input type="text" id="piloto_'+num+'" class="form-control piloto text-uppercase text-center"></td>\n\
                            <td><input type="text" name="km[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="6" placeholder="0"></td>\n\
                            <td><input type="text" name="xtanque[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0"></td>\n\
                            <td><input type="text" name="qabast[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"></td>\n\
                            <td><input type="text" name="observ[]" class="form-control text-uppercase text-center" maxlength="255"></td>\n\
                            <td><input type="text" name="ingreso[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"></td>\n\
                            <td><input type="text" name="salida[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"></td>\n\
                            <td><input type="text" name="stop[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"><input type="hidden" name="consumo[]" value="'+data[i].rte_consumo+'"></td>\n\
                            </tr>';
                        $("#consumodet").html(html);
                    }
                    for(j=1;j<=num;j++)
                    {
                        autocompletar_personas('conductor_'+j);
                        autocompletar_personas('piloto_'+j);
                    }
                    $("#btn_vw_consumoscab_Guardar").removeAttr('disabled');
                    swal.close();
                }
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
});

// GUARDAR OTROS CONSUMOS

function evaluar()
{
    if (detalles>0)
    {
        $("#btn_vw_otrosconsumos_Guardar").show();
    }
    else
    {
      $("#btn_vw_otrosconsumos_Guardar").hide();
      cont=0;
    }
}

function eliminarDetalle(indice)
{
    $("#filas_consumocab_"+indice).remove();
    detalles=detalles-1;
    cont++;
}

jQuery(document).on("click", "#agregar_otrasrutas", function(){
    var fila='<tr class="filas_consumocab" id="filas_consumocab_'+cont+'">'+
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')"><i class="fa fa-trash-o"></i></button></td>'+
            '<td><input type="text" name="fecha[]" class="form-control text-uppercase text-center otro" readonly="readonly"></td>'+
            '<td><input type="hidden" name="contador[]"><input type="hidden" name="estacion[]" id="hiddenestacion_'+cont+'"><input type="text" id="estacion_'+cont+'" class="form-control text-uppercase text-center"></td>'+
            '<td><input type="hidden" name="conductor[]" id="hiddenconductor_'+cont+'"><input type="text" id="conductor_'+cont+'" class="form-control conductor text-uppercase text-center"></td>'+
            '<td><input type="hidden" name="piloto[]" id="hiddenpiloto_'+cont+'"><input type="text" id="piloto_'+cont+'" class="form-control piloto text-uppercase text-center"></td>'+
            '<td><input type="text" name="km[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="6" placeholder="0"></td>'+
            '<td><input type="text" name="xtanque[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0"></td>'+
            '<td><input type="text" name="qabast[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"></td>'+
            '<td><input type="text" name="observ[]" class="form-control text-uppercase text-center" maxlength="255"></td>'+
            '<td><input type="text" name="ingreso[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"></td>'+
            '<td><input type="text" name="salida[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"></td>'+
            '<td><input type="text" name="stop[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8" placeholder="0.000"></td>'+
            '</tr>';
    autocompletar_estaciones('estacion_'+cont);
    autocompletar_personas('conductor_'+cont);
    autocompletar_personas('piloto_'+cont);
    cont++;
    detalles=detalles+1;
    $('#cuerpodet').append(fila);
});

jQuery(document).on("click", "#btn_vw_otrosconsumos_Guardar", function(){
    $("#cbx_consumo_ruta").removeAttr('disabled');
    var form = new FormData($("#FormularioConsumoDetalle")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo?tipo=1&capacidad='+$('#cbx_capacidad').val(),
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        data:form,
        success: function (data) 
        {
            if (data == 1) 
            {
                MensajeConfirmacion('EL RESPUESTA FUE ENVIADA CON EXITO');
                jQuery("#tblconsumosdet").jqGrid('setGridParam', {
                    url: 'consumo/0?grid=consumos'
                }).trigger('reloadGrid');
                $(".filas_consumocab").remove();
                mostrarformulario(false);
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

// GUARDAR CONSUMOS NORMALES

jQuery(document).on("click", "#btn_vw_consumoscab_Guardar", function() {
    
    var form = new FormData($("#FormularioConsumoDetalle")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo?tipo=1&capacidad='+$('#cbx_capacidad').val(),
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        data:form,
        success: function (data) 
        {
            if (data == 1) 
            {
                MensajeConfirmacion('EL RESPUESTA FUE ENVIADA CON EXITO');
                jQuery("#tblconsumosdet").jqGrid('setGridParam', {
                    url: 'consumo/0?grid=consumos'
                }).trigger('reloadGrid');
                $(".filas_consumocab").remove();
                mostrarformulario(false);
            }
            else if(data.msg == 'validator') 
            {
                MensajeAdvertencia(data.error[0]);
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

jQuery(document).on("click", "#btn_act_tblconsumos", function(){
    $("#txt_buscar_nrovale").val('');
    $("#txt_buscar_placa").val('');
    $("#txt_buscar_fdesde").val('');
    $("#txt_buscar_fhasta").val('');
    $("#txt_buscar_ruta").val('');
    $("#txt_buscar_estacion").val('');
    
    jQuery("#tblconsumosdet").jqGrid('setGridParam', {
        url: 'consumo/0?grid=consumos'
    }).trigger('reloadGrid');
});

function modificar_consumodetalle(cde_id){
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo/'+cde_id+'?show=datos',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION');  
        },
        success: function(data) 
        {
            Consumo = $('#ModalConsumos').modal({backdrop: 'static', keyboard: false});
            Consumo.find('.modal-title').text('EDITAR CONSUMO');
            Consumo.find('#btn_actualizar_consumo').html('<i class="fa fa-pencil-square-o"></i> MODIFICAR').show();

            $("#lbl_cde_vale").html("VALE: " + "<b>"+data.nro_vale+"</b>");
            $("#lbl_cde_placa").html("PLACA: " + "<b>"+data.veh_placa+"</b>");
            $("#lbl_cde_ruta").html("RUTA: " + "<b>"+data.rut_descripcion+"</b>");
            $("#lbl_cde_estacion").html("ESTACION: " + "<b>"+data.est_descripcion+"</b>");
            $("#txt_cde_conductor").val(data.idconductor);
            $("#txt_cde_copiloto").val(data.idcopiloto);

            $("#txt_cde_copiloto").change();
            $("#txt_cde_conductor").change();      

            $("#txt_cde_fecha").val(data.cde_fecha);
            $("#txt_cde_km").val(data.cde_kilometros);
            $("#txt_cde_xtanque").val(data.cde_xtanque);
            if (data.cde_qparcial === 1) 
            {
                $("#txt_cde_qabast").val(data.cde_qabastecida);
                $("#txt_cde_qabast").attr('disabled',true);
                $("#cabeceraModalConsumo").show();
            }
            else
            {
                $("#txt_cde_qabast").val(data.cde_qabastecida);
                $("#txt_cde_qabast").removeAttr('disabled');
                $("#cabeceraModalConsumo").hide();
            }
            $("#txt_cde_observaciones").val(data.cde_observaciones);
            $("#txt_cde_ingreso").val(data.cde_ingreso);
            $("#txt_cde_salida").val(data.cde_salida);
            $("#txt_cde_stop").val(data.cde_stop);
            if(data.cde_comentario != '-')
            {
                $("#div_comentario").show();
                $("#lbl_cde_comentario").text(data.cde_comentario);
            }
            else
            {
                $("#div_comentario").hide();
            }
            
            if (rol_descripcion === 'ADMINISTRADOR' && data.cde_comentario != '-') 
            {
                fn_leer_comentario(cde_id);
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

jQuery(document).on("click", "#btn_actualizar_consumo", function(){
    cde_id = $('#tblconsumosdet').jqGrid ('getGridParam', 'selrow');
    
    if ($('#txt_cde_fecha').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA ES OBLIGATORIO...', '#txt_cde_fecha');
        return false;
    }
    if ($('#txt_cde_conductor').val() == '') {
        mostraralertasconfoco('* EL CAMPO CONDUCTOR ES OBLIGATORIO...', '#txt_cde_conductor');
        return false;
    }
    if ($('#txt_cde_copiloto').val() == '') {
        mostraralertasconfoco('* EL CAMPO COPILOTO ES OBLIGATORIO...', '#txt_cde_copiloto');
        return false;
    }
    if ($('#txt_cde_km').val() == '') {
        mostraralertasconfoco('* EL CAMPO KILOMETRO ES OBLIGATORIO...', '#txt_cde_km');
        return false;
    }
    if ($('#txt_cde_xtanque').val() == '') {
        mostraralertasconfoco('* EL CAMPO STOP EN TANQUE ES OBLIGATORIO...', '#txt_cde_xtanque');
        return false;
    }
    if ($('#txt_cde_qabast').val() == '') {
        mostraralertasconfoco('* EL CAMPO Q-ABASTECIDA ES OBLIGATORIO...', '#txt_cde_qabast');
        return false;
    }
    if ($('#txt_cde_observaciones').val() == '') {
        mostraralertasconfoco('* EL CAMPO OBSERVACIONES ES OBLIGATORIO...', '#txt_cde_observaciones');
        return false;
    }
    if ($('#txt_cde_ingreso').val() == '') {
        mostraralertasconfoco('* EL CAMPO INGRESO ES OBLIGATORIO...', '#txt_cde_ingreso');
        return false;
    }
    if ($('#txt_cde_salida').val() == '') {
        mostraralertasconfoco('* EL CAMPO SALIDA ES OBLIGATORIO...', '#txt_cde_salida');
        return false;
    }
    if ($('#txt_cde_stop').val() == '') {
        mostraralertasconfoco('* EL CAMPO STOP ES OBLIGATORIO...', '#txt_cde_stop');
        return false;
    }
    
    $.ajax({
        url: 'consumo/'+cde_id+'/edit',
        type: 'GET',
        data:
        {
            tri_idconductor:$('#txt_cde_conductor').val(),
            tri_idcopiloto:$('#txt_cde_copiloto').val(),
            cde_fecha:$('#txt_cde_fecha').val(),
            cde_kilometros:$('#txt_cde_km').val(),
            cde_xtanque:$('#txt_cde_xtanque').val(),
            cde_qabastecida:$('#txt_cde_qabast').val(),
            cde_observaciones:$('#txt_cde_observaciones').val(),
            cde_ingreso:$('#txt_cde_ingreso').val(),
            cde_salida:$('#txt_cde_salida').val(),
            cde_stop:$('#txt_cde_stop').val(),
            capacidad:$('#cbx_capacidad').val(),
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
                jQuery("#tblconsumosdet").jqGrid('setGridParam', {
                    url: 'consumo/0?grid=consumos'
                }).trigger('reloadGrid');
                $('#btn_cerrar_modal_consumo').click();
            }
            else if(data.msg == 'validator') 
            {
                MensajeAdvertencia(data.error[0]);
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

jQuery(document).on("click", "#btn_vw_buscar_consumos", function(){
    jQuery("#tblconsumosdet").jqGrid('setGridParam', {
        url: 'consumo/0?grid=consumos&indice=1&nrovale='+$("#txt_buscar_nrovale").val()+'&placa='+$("#txt_buscar_placa").val()+'&fdesde='+$("#txt_buscar_fdesde").val()+'&fhasta='+$("#txt_buscar_fhasta").val()+'&ruta='+$("#txt_buscar_ruta").val()+'&estacion='+$("#txt_buscar_estacion").val()
    }).trigger('reloadGrid');
});

jQuery(document).on("click", "#btn_vw_nuevo_consumo_or", function(){
    RutaConsumo = $('#ModalNuevaRuta').modal({backdrop: 'static', keyboard: false});
    RutaConsumo.find('.modal-title').text('NUEVA RUTA');
    RutaConsumo.find('#btn_crear_nueva_ruta').html('<i class="fa fa-sign-in"></i> AGREGAR NUEVO').show();
    
    $('.modal_new').attr('disabled',true);
    $('.modal_new').val('');
    $("#txt_new_nrovale").val('');
    setTimeout(function (){
        $('#txt_new_nrovale').focus();
    }, 200);
});

$("#txt_new_nrovale").keypress(function (e) {
    if (e.which == 13) {
        fn_buscar_nrovale();
    }
});

function fn_buscar_nrovale()
{
    if ($('#txt_new_nrovale').val() == '') {
        mostraralertasconfoco('* EL CAMPO NUMERO VALE NO PUEDE ESTAR EN BLANCO...', '#txt_new_nrovale');
        return false;
    }
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo/0?datos=datos_nrovale',
        type: 'GET',
        data:
        {
            nrovale:$("#txt_new_nrovale").val(),
        },
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION');  
        },
        success: function(data) 
        {
            if (data.rut_descripcion == 'OTR') 
            {
                $('.modal_new').removeAttr('disabled');
                autocompletar_estaciones('txt_new_estacion');
                autocompletar_personas('txt_new_conductor');
                autocompletar_personas('txt_new_copiloto');
                $("#txt_new_cca_id").val(data.cca_id);
                $("#txt_new_veh_id").val(data.veh_id);
                $("#txt_new_rut_id").val(data.rut_id);
                swal.close();
            }
            else if(data == 0)
            {
                mostraralertasconfoco('EL NUMERO DE VALE NO EXISTE');
                $('.modal_new').attr('disabled',true);
                $('.modal_new').val('');
            }
            else
            {
                mostraralertasconfoco('EL NUMERO DE VALE NO ES "OTR" ');
                $('.modal_new').attr('disabled',true);
                $('.modal_new').val('');
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

jQuery(document).on("click", "#btn_crear_nueva_ruta", function(){
    var now = new Date();
    var fecha = now.getFullYear() + '-' + now.getMonth() + '-' + now.getDate();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo/create',
        type: 'GET',
        data:
        {
            cde_fecha:$('#txt_new_fecha').val() || fecha,
            cca_id:$('#txt_new_cca_id').val(),
            veh_id:$('#txt_new_veh_id').val(),
            rut_id:$('#txt_new_rut_id').val(),
            est_id:$('#hiddentxt_new_estacion').val() || 1,
            tri_idconductor:$('#hiddentxt_new_conductor').val() || 1,
            tri_idcopiloto:$('#hiddentxt_new_copiloto').val() || 1,
            cde_kilometros:$('#txt_new_kilometros').val() || 0,
            cde_xtanque:$('#txt_new_xtanque').val() || 0,
            cde_qabastecida:$('#txt_new_qabast').val() || 0.0,
            cde_observaciones:$('#txt_new_observaciones').val() || '-',
            cde_ingreso:$('#txt_new_ingreso').val() || 0.0,
            cde_salida:$('#txt_new_salida').val() || 0.0,
            cde_stop:$('#txt_cde_stop').val() || 0.0,
            capacidad:$('#cbx_capacidad').val(),
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
                MensajeConfirmacion('SE AGREGO EL REGISTRO CON EXITO');
                jQuery("#tblconsumosdet").jqGrid('setGridParam', {
                    url: 'consumo/0?grid=consumos'
                }).trigger('reloadGrid');
                $('.modal_new').attr('disabled',true);
                $('.modal_new').val('');
                $("#txt_new_nrovale").val('');
                $("#txt_new_nrovale").focus();
            }
            else if(data.msg == 'validator') 
            {
                MensajeAdvertencia(data.error[0]);
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

function consumo_parcial(cde_id,cde_qparcial)
{
    if (cde_qparcial === 0)
    {
        ConsumoParcial = $('#ModalConsumoQparcial').modal({backdrop: 'static', keyboard: false});
        ConsumoParcial.find('.modal-title').text('AGREGAR Q-ABAST. PARCIALES');
        ConsumoParcial.find('#btn_agregar_cqparciales').html('<i class="fa fa-sign-in"></i> AGREGAR Q-ABAST.').show();
        $('#principal').show();
        $('#secundario').hide();
        $('#btn_agregar_cqparciales').show();
        $('#btn_agregar_cqparciales').attr('disabled',true);
        $('#btn_actualizar_cqparciales').hide();
        $(".qabastparciales").remove();
        cont_qparcial=0;
        det_qparcial=0;
        $("#lbl_conpar_vale").html("VALE: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'nro_vale')+"</b>");
        $("#lbl_conpar_placa").html("PLACA: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'veh_placa')+"</b>");
        $("#lbl_conpar_estacion").html("ESTACION: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'est_descripcion')+"</b>");
        $("#lbl_conpar_ruta").html("RUTA: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'rut_descripcion')+"</b>");
    }
    else
    {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'consumo/'+cde_id+'?show=datos_qparcial',
            type: 'GET',
            beforeSend:function()
            {            
                MensajeEspera('RECUPERANDO INFORMACION');  
            },
            success: function(data) 
            {
                html_detalle = "";
                ConsumoParcial = $('#ModalConsumoQparcial').modal({backdrop: 'static', keyboard: false});
                ConsumoParcial.find('.modal-title').text('VER Q-ABAST. PARCIALES');
                $("#lbl_conpar_vale").html("VALE: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'nro_vale')+"</b>");
                $("#lbl_conpar_placa").html("PLACA: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'veh_placa')+"</b>");
                $("#lbl_conpar_estacion").html("ESTACION: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'est_descripcion')+"</b>");
                $("#lbl_conpar_ruta").html("RUTA: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'rut_descripcion')+"</b>");
                
                for(i=0;i<data.length;i++)
                {
                    html_detalle = html_detalle+'<div class="col-md-12 text-center">\n\
                                                    <div class="form-group text-center">\n\
                                                        <h4>'+data[i].cdp_qparcial+'</h4>\n\
                                                    </div>\n\
                                                </div>';
                    $("#detalle_qabast_parcial_2").html(html_detalle);
                }
                $('#principal').hide();
                $('#secundario').show();
                $('#btn_actualizar_cqparciales').hide();
                $('#btn_agregar_cqparciales').hide();
                swal.close();
            },
            error: function(data) {
                MensajeAdvertencia("hubo un error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
            }
        });
    }
}

var cont_qparcial=0;
var det_qparcial=0;
jQuery(document).on("click", "#btn_generar_cqparciales", function(){
    var detalleQparcial =  '<div class="col-md-12 qabastparciales filaPacial_'+cont_qparcial+'">'+
                                '<div class="form-group">'+
                                    '<label>Q-ABAST PARCIAL:</label>'+
                                    '<div class="input-group">'+
                                        '<div class="input-group-prepend">'+
                                            '<span class="input-group-text"><i class="fa fa-tasks"></i></span>'+
                                        '</div>'+
                                        '<input type="hidden" name="contarqabastParcial[]"><input type="text" name="cdp_qparcial[]" class="form-control text-center" maxlength="8" onkeypress="return soloNumeroTab(event);">'+
                                        '<button type="button" class="btn btn-danger" onclick="EliminarDetQparcial('+cont_qparcial+')"><i class="fa fa-trash-o"></i></button>'+
                                        '<div name="otro[]"></div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
    cont_qparcial++;
    det_qparcial = det_qparcial + 1;
    $('#detalle_qabast_parcial_1').append(detalleQparcial);
    evaluarDetQparcial();
});

function EliminarDetQparcial(indice)
{
    $(".filaPacial_"+indice).remove();
    det_qparcial = det_qparcial - 1;
    cont_qparcial++;
    evaluarDetQparcial();
}

function evaluarDetQparcial()
{
    console.log(det_qparcial);
    if (det_qparcial>0)
    {
        $("#btn_agregar_cqparciales").removeAttr('disabled');
    }
    else
    {
        $('#btn_agregar_cqparciales').attr('disabled',true);
        cont_qparcial = 0;
    }
}

jQuery(document).on("click", "#btn_agregar_cqparciales", function(){
    cde_id = $('#tblconsumosdet').jqGrid ('getGridParam', 'selrow');
    var QabastPart = new FormData($("#FormularioQabastParcial")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo?tipo=2&cde_id='+cde_id,
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        data: QabastPart,
        success: function (data) 
        {
            if (data == 1) 
            {
                MensajeConfirmacion('EL RESPUESTA FUE ENVIADA CON EXITO');
                jQuery("#tblconsumosdet").jqGrid('setGridParam', {
                    url: 'consumo/0?grid=consumos'
                }).trigger('reloadGrid');
                $("#btn_cerrar_modal_1").click();
            }
            else
            {
                console.log(data.error);
                datos = $("input[name='cdp_qparcial[]']");
                $.each(data.error, function(key, value){                        
                    console.log(key);
                    console.log(value);
                    MensajeAdvertencia("<li>"+value+"</li>");
                });
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
});

jQuery(document).on("click","#btn_modificar_qparcial", function(){
    cde_id = $('#tblconsumosdet').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo/'+cde_id+'?show=datos_qparcial',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION');  
        },
        success: function(data) 
        {
            html_detalle = "";
            $("#btn_cerrar_modal").click();
            ConsumoParcial = $('#ModalConsumoQparcial').modal({backdrop: 'static', keyboard: false});
            ConsumoParcial.find('.modal-title').text('VER Q-ABAST. PARCIALES');
            $("#lbl_conpar_vale").html("VALE: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'nro_vale')+"</b>");
            $("#lbl_conpar_placa").html("PLACA: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'veh_placa')+"</b>");
            $("#lbl_conpar_estacion").html("ESTACION: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'est_descripcion')+"</b>");
            $("#lbl_conpar_ruta").html("RUTA: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'rut_descripcion')+"</b>");

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
                $("#detalle_qabast_parcial_2").html(html_detalle);
            }
            $('#principal').hide();
            $('#secundario').show();
            $('#btn_actualizar_cqparciales').show();
            $('#btn_agregar_cqparciales').hide();
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
    cde_id = $('#tblconsumosdet').jqGrid ('getGridParam', 'selrow');
    var QabastPartmod = new FormData($("#FormularioQabastParcialMod")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo?tipo=3&cde_id='+cde_id,
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
                jQuery("#tblconsumosdet").jqGrid('setGridParam', {
                    url: 'consumo/0?grid=consumos'
                }).trigger('reloadGrid');
                $("#btn_cerrar_modal_1").click();
            }
            else
            {
                console.log(data.error);
                $.each(data.error, function(key, value){                        
                    console.log(key);
                    console.log(value);
                    MensajeAdvertencia("<li>"+value+"</li>");
                });
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
});

jQuery(document).on("click", "#btn_agregar_comentario", function(){
    cde_id = $('#tblconsumosdet').jqGrid ('getGridParam', 'selrow');
    
    $.ajax({
        url: 'consumo/'+cde_id+'/?show=traer_datos_comentario',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION');  
        },
        success: function(data) 
        {
            Comentario = $('#ModalComentario').modal({backdrop: 'static', keyboard: false});
            Comentario.find('.modal-title').text('CREAR NUEVA CAPACIDAD');
            Comentario.find('#btn_crear_comentario').html('<i class="fa fa-sign-in"></i> AGREGAR NUEVO').show();
            $("#txt_cde_comentario").val(data[0].cde_comentario);
            setTimeout(function (){
                $('#txt_cde_comentario').focus();
            }, 200);
            swal.close();
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
});

jQuery(document).on("click", "#btn_crear_comentario", function(){
    cde_id = $('#tblconsumosdet').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url: 'consumo/'+cde_id+'/edit',
        type: 'GET',
        data:
        {
            cde_comentario:$('#txt_cde_comentario').val() || '-',
            tipo:2
        },
        beforeSend:function()
        {            
            MensajeEspera('ENVIANDO INFORMACION');  
        },
        success: function(data) 
        {
            if (data.msg == 1) 
            {
                MensajeConfirmacion('SE REGISTRO EL COMENTARIO CON EXITO');
                $("#lbl_cde_comentario").text(data.respuesta.cde_comentario);
                $("#btn_cerrar_modal_comentario").click();
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

jQuery(document).on("click","#btn_modificar_estacion_adm",function(){
    ModificarRuta = $('#ModalModificarRuta').modal({backdrop: 'static', keyboard: false});
    ModificarRuta.find('.modal-title').text('MODIFICAR ESTACION');
    ModificarRuta.find('#btn_actualizar_estacion').html('<i class="fa fa-pencil-square-o"></i> MODIFICAR').show();
    $("#hiddentxt_cde_est_id").val('');
    $("#txt_cde_est_id").val('');
    setTimeout(function (){
        $('#txt_cde_est_id').focus();
    }, 200);
    autocompletar_estaciones('txt_cde_est_id');
});

function fn_modificar_estacion_adm()
{
    cde_id = $('#tblconsumosdet').jqGrid ('getGridParam', 'selrow');
    nro_vale = $('#tblconsumosdet').jqGrid ('getCell', cde_id, 'nro_vale');
    rut_descripcion = $('#tblconsumosdet').jqGrid ('getCell', cde_id, 'rut_descripcion');
    est_descripcion = $('#tblconsumosdet').jqGrid ('getCell', cde_id, 'est_descripcion');
    if ($('#hiddentxt_cde_est_id').val() == '') {
        mostraralertasconfoco('* EL CAMPO ESTACION ES OBLIGATORIO...', '#txt_cde_est_id');
        return false;
    }
    
    swal({
        title: '¿ESTA SEGURO DE CAMBIAR LA ESTACION?',
        html: "<b>N° VALE: </b>"+nro_vale+"<br> <b>RUTA: </b>"+rut_descripcion+ '<br> <b>ESTACION ANTIGUA ---> </b>'+est_descripcion+ '<br> <b>ESTACION NUEVA ---> </b>'+$("#txt_cde_est_id").val(),
        type: 'warning',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ACEPTAR',
        cancelButtonText: 'CANCELAR',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        showCancelButton: true,
        buttonsStyling: false,
        reverseButtons: true
        }).then(function(result) {
            $.ajax({
                url: 'consumo/'+cde_id+'/edit',
                type: 'GET',
                data:
                {
                    est_id:$('#hiddentxt_cde_est_id').val(),
                    tipo:3
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
                        jQuery("#tblconsumosdet").jqGrid('setGridParam', {
                            url: 'consumo/0?grid=consumos'
                        }).trigger('reloadGrid');
                        $('#btn_cerrar_modal_modificar_ruta').click();
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
        }, function(dismiss) {
            console.log("OPERACION CANCELADA");
        });
}

jQuery(document).on("click", "#btn_agregar_estacion_adm", function(){
    cde_id = $('#tblconsumosdet').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo/'+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'cca_id')+'?show=datos_estaciones',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION');  
        },
        success: function(data) 
        {
            contNuevaRuta = 0;
            html_detalle = "";
            AgregarNuevaRuta = $('#ModalAgregarNuevaRutaConsumo').modal({backdrop: 'static', keyboard: false});
            AgregarNuevaRuta.find('.modal-title').text('AGREGAR ESTACIONES NUEVAS');
            $("#lbl_anrc_vale").html("VALE: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'nro_vale')+"</b>");
            $("#lbl_anrc_placa").html("PLACA: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'veh_placa')+"</b>");
            $("#lbl_anrc_ruta").html("RUTA: " + "<b>"+$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'rut_descripcion')+"</b>");
            $('#btn_act_estaciones_nuevas').attr('disabled',true);

            for(i=0;i<data.length;i++)
            {
                html_detalle = html_detalle + '<tr class="filasRutas_'+i+'">\n\
                                                <td><button type="button" title="AGREGAR REGISTRO" class="btn btn-success btn-round-animate" onclick="agregar_nuevas_rutas('+i+')"><i class="fa fa-plus-square"></i></button></td>\n\
                                                <td><input type="hidden" name="vw_consumos_cde_id[]" value="'+data[i].cde_id+'"><input type="hidden" name="id_estacion[]" value="'+data[i].est_id+'">'+data[i].est_descripcion+'</td>\n\
                                            </tr>';
                $("#DetalleNuevaRutaConsumo").html(html_detalle);
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

var contNuevaRuta = 0;
var detallesNuevaRuta = 0;

function agregar_nuevas_rutas(contador)
{
    var fila='<tr class="filasNuevaRuta_'+contNuevaRuta+'">\n\
                <td><button type="button" title="BORRAR REGISTRO" class="btn btn-danger btn-round-animate" onclick="eliminar_nuevas_rutas('+contNuevaRuta+')"><i class="fa fa-trash"></i></button>&nbsp;&nbsp;<button type="button" title="AGREGAR ESTACION" class="btn btn-primary btn-round-animate btn_validacion_ruta" onclick="agregar_nueva_ruta('+contNuevaRuta+');"><i class="fa fa-check"></i></button></td>\n\
                <td><input type="hidden" name="id_estacion[]" class="validar_ruta" id="hiddendescripcion_estacion_'+contNuevaRuta+'"><input type="text" class="form-control text-center text-uppercase" placeholder="ESCRIBIR NOMBRE ESTACION" name=descripcion_estacion[] id="descripcion_estacion_'+contNuevaRuta+'"></td>\n\
            </tr>';
    
    autocompletar_estaciones('descripcion_estacion_'+contNuevaRuta);
    contNuevaRuta++;
    detallesNuevaRuta=detallesNuevaRuta+1;
    $('.filasRutas_'+contador).after(fila);
    evaluar_nueva_ruta();
}

function eliminar_nuevas_rutas(indice)
{
    $(".filasNuevaRuta_"+indice).remove();
    detallesNuevaRuta=detallesNuevaRuta-1;
    contNuevaRuta++;
    evaluar_nueva_ruta();
}

function evaluar_nueva_ruta()
{
    if (detallesNuevaRuta>0)
    {
        $("#btn_act_estaciones_nuevas").removeAttr('disabled');
        $("#btn_cerrar_modal_AgregarNuevaRutaConsumo").hide();
    }
    else
    {
        $("#btn_cerrar_modal_AgregarNuevaRutaConsumo").show();
        $('#btn_act_estaciones_nuevas').attr('disabled',true);
        contNuevaRuta=0;
    }
}

jQuery(document).on("click","#btn_cerrar_modal",function(){
    jQuery("#tblconsumosdet").jqGrid('setGridParam', {
        url: 'consumo/0?grid=consumos'
    }).trigger('reloadGrid');
});

function fn_leer_comentario(cde_id)
{
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo/'+cde_id+'/edit',
        type: 'GET',
        data:
        {
            tipo:4
        },
        success: function(data) 
        {
            if (data == 1) 
            {
                console.log(data);
            }
            else
            {
                MensajeAdvertencia('ERROR AL LEER EL COMENTARIO');
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

function agregar_nueva_ruta(valor)
{
    cde_id = $('#tblconsumosdet').jqGrid ('getGridParam', 'selrow');
    if ($('#hiddendescripcion_estacion_'+valor).val() == '') {
        mostraralertasconfoco('* EL NOMBRE DE LA ESTACION ES OBLIGATORIO...', '#descripcion_estacion_'+valor);
        return false;
    }
    
    swal({
        title: '¿ESTA SEGURO DE REGISTRAR ESTA ESTACION?',
        html: '<b>ESTACION: '+$('#descripcion_estacion_'+valor).val()+'</b>',
        type: 'warning',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ACEPTAR',
        cancelButtonText: 'CANCELAR',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        showCancelButton: true,
        buttonsStyling: false,
        reverseButtons: true
        }).then(function(result) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: 'consumo/create',
                type: 'GET',
                data:
                {
                    cca_id:$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'cca_id'),
                    veh_id:$('#tblconsumosdet').jqGrid ('getCell', cde_id, 'veh_id'),
                    est_id:$('#hiddendescripcion_estacion_'+valor).val(),
                    tipo:2
                },
                beforeSend:function()
                {            
                    MensajeEspera('ENVIANDO INFORMACION');  
                },
                success: function(data) 
                {
                    $(".filasNuevaRuta_"+valor).empty();
                    html_detalle_nr = ''
                    if (data.msg == 1) 
                    {
                        html_detalle_nr = '<td></td>\n\
                        <td><input type="hidden" name="vw_consumos_cde_id[]" value="'+data.respuesta.cde_id+'"><input type="text" class="form-control text-center text-uppercase" disabled="disabled" value="'+data.respuesta.est_descripcion+'"></td>';
                        
                        $(".filasNuevaRuta_"+valor).html(html_detalle_nr);
                        swal.close();
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
        }, function(dismiss) {
            console.log("OPERACION CANCELADA");
        });
}

jQuery(document).on("click", "#btn_act_estaciones_nuevas", function(){
    
    if ($('.validar_ruta').val() == '') {
        mostraralertasconfoco('* EL CAMPO ESTACION NO PUEDE ESTAR VACIO...');
        return false;
    }
    
    if ($('.btn_validacion_ruta').length > 0) {
        mostraralertasconfoco('* DEBES GUARDAR LA ESTACION SELECCIONADA...');
        return false;
    }
    
    var datosNuevasRutas = new FormData($("#FormularioNuevasRutasConsumo")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'consumo?tipo=4',
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        data: datosNuevasRutas,
        success: function (data) 
        {
            if (data == 1) 
            {
                MensajeConfirmacion('EL RESPUESTA FUE ENVIADA CON EXITO');
                jQuery("#tblconsumosdet").jqGrid('setGridParam', {
                    url: 'consumo/0?grid=consumos'
                }).trigger('reloadGrid');
                detallesNuevaRuta = 0;
                $("#btn_cerrar_modal_AgregarNuevaRutaConsumo").click();
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