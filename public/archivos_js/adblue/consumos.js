jQuery(document).ready(function($){
    $('#men_registro').addClass('menu-open');
    $('a[href*="#1"]').addClass('active');
    $('.consumo').addClass('active');
    
    mostrarformulario(false);
    
    jQuery("#tblconsumosdet").jqGrid({
        url: 'consumo/0?grid=consumos&indice=0',
        datatype: 'json', mtype: 'GET',
        height: '470px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        shrinkToFit: false,
        forceFit:true,  
        scroll: false,
        colNames: ['ID', 'FECHA','IDCAB','VALE','PLACA','RUTA','ESTACION','CONDUCTOR','COPILOTO','KM','%','Q-LT','%','Q-LT','Q-ABAST','OBSERVACIONES','INGRESO','SALIDA','STOP'],
        rowNum: 20, sortname: 'cde_id', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblconsumos" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE CONSUMOS -', align: "center",
        colModel: [
            {name: 'cde_id', index: 'cde_id', align: 'left',width: 10,hidden:true,frozen:true},
            {name: 'cde_fecha', index: 'cde_fecha', align: 'center', width: 90,frozen:true},
            {name: 'cca_id', index: 'cca_id', align: 'center', width: 70,hidden:true,frozen:true},
            {name: 'nro_vale', index: 'nro_vale', align: 'center', width: 55,frozen:true},
            {name: 'veh_placa', index: 'veh_placa', align: 'center', width: 80,frozen:true},
            {name: 'rut_descripcion', index: 'rut_descripcion', align: 'left', width: 70},
            {name: 'est_descripcion', index: 'est_descripcion', align: 'left', width: 90},
            {name: 'conductor', index: 'conductor', align: 'left', width: 350},
            {name: 'copiloto', index: 'copiloto', align: 'left', width: 350},
            {name: 'cde_kilometros', index: 'cde_kilometros', align: 'center', width: 70},
            {name: 'cde_xtanque', index: 'cde_xtanque', align: 'center', width: 60,formatter: 'integer',formatoptions: { suffix: '%' }},
            {name: 'cde_qlttanque', index: 'cde_qlttanque', align: 'center', width: 70},
            {name: 'cde_xconsumida', index: 'cde_xconsumida', align: 'center', width: 70,formatter: 'integer',formatoptions: { suffix: '%' }},
            {name: 'cde_qltconsumida', index: 'cde_qltconsumida', align: 'center', width: 70},
            {name: 'cde_qabastecida', index: 'cde_qabastecida', align: 'center', width: 70},
            {name: 'cde_observaciones', index: 'cde_observaciones', align: 'left', width: 350},
            {name: 'cde_ingreso', index: 'cde_ingreso', align: 'center', width: 70},
            {name: 'cde_salida', index: 'cde_salida', align: 'center', width: 70},
            {name: 'cde_stop', index: 'cde_stop', align: 'center', width: 70},
        ],
        pager: '#paginador_tblconsumosdet',
        rowList: [10, 20, 30, 40, 50],
        gridComplete: function () {
                var idarray = jQuery('#tblconsumosdet').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                var firstid = jQuery('#tblconsumosdet').jqGrid('getDataIDs')[0];
                    $("#tblconsumosdet").setSelection(firstid);    
                }
            },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){modificar_consumodetalle(Id);}
    });
    
    $('#tblconsumosdet').setGroupHeaders(
    {
        useColSpanStyle: true,
        groupHeaders: [
            { "numberOfColumns": 5, "titleText": "<center><h5>RESUMEN</h5></center>", "startColumnName": "rut_descripcion",align: 'center'},
            { "numberOfColumns": 2, "titleText": "<center><h5>STOP-TANQ.</h5></center>", "startColumnName": "cde_xtanque",align: 'center' },
            { "numberOfColumns": 2, "titleText": "<center><h5>Q-CONS.</h5></center>", "startColumnName": "cde_xconsumida",align: 'center' },
            { "numberOfColumns": 2, "titleText": "<center><h5>ABASTECIMIENTO</h5></center>", "startColumnName": "cde_qabastecida",align: 'center' },
            { "numberOfColumns": 3, "titleText": "<center><h5>RESERVA</h5></center>", "startColumnName": "cde_ingreso",align: 'center' }]
    });
    
    $("#tblconsumosdet").jqGrid("destroyFrozenColumns")
            .jqGrid("setColProp", "cde_fecha", { frozen: true })
            .jqGrid("setColProp", "nro_vale", { frozen: true })
            .jqGrid("setColProp", "veh_placa", { frozen: true })
            .jqGrid("setFrozenColumns")
            .trigger("reloadGrid", [{ current: true}]);
    
    $(window).on('resize.jqGrid', function () {
        $("#tblconsumosdet").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
    $(".select2").select2();
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
        $("#btn_vw_consumocab").hide();
        $("#btn_vw_nuevo_consumo_or").hide();
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
        $("#btn_vw_consumocab").show();
        $("#btn_vw_nuevo_consumo_or").show();
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
                if (data[0].rut_descripcion == 'OR') 
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
                                                <td><input type="date" name="fecha[]" class="form-control text-uppercase text-center"></td>\n\
                                                <td><input type="hidden" name="contador[]"><input type="hidden" name="estacion[]" id="hiddenestacion_'+num+'"><input type="text" id="estacion_'+num+'" class="form-control text-uppercase text-center"></td>\n\
                                                <td><input type="hidden" name="conductor[]" id="hiddenconductor_'+num+'"><input type="text" id="conductor_'+num+'" class="form-control conductor text-uppercase text-center"></td>\n\
                                                <td><input type="hidden" name="piloto[]" id="hiddenpiloto_'+num+'"><input type="text" id="piloto_'+num+'" class="form-control piloto text-uppercase text-center"></td>\n\
                                                <td><input type="text" name="km[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="6"></td>\n\
                                                <td><input type="text" name="xtanque[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>\n\
                                                <td><input type="text" name="qabast[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>\n\
                                                <td><input type="text" name="observ[]" class="form-control text-uppercase text-center" maxlength="255"></td>\n\
                                                <td><input type="text" name="ingreso[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>\n\
                                                <td><input type="text" name="salida[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>\n\
                                                <td><input type="text" name="stop[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>\n\
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
                            <td><input type="date" name="fecha[]" class="form-control text-uppercase text-center"></td>\n\
                            <td><input type="hidden" name="contador[]"><input type="hidden" name="estacion[]" value="'+data[i].est_id+'"><label>'+data[i].est_descripcion+'</label></td>\n\
                            <td><input type="hidden" name="conductor[]" id="hiddenconductor_'+num+'"><input type="text" id="conductor_'+num+'" class="form-control conductor text-uppercase text-center"></td>\n\
                            <td><input type="hidden" name="piloto[]" id="hiddenpiloto_'+num+'"><input type="text" id="piloto_'+num+'" class="form-control piloto text-uppercase text-center"></td>\n\
                            <td><input type="text" name="km[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="6"></td>\n\
                            <td><input type="text" name="xtanque[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>\n\
                            <td><input type="text" name="qabast[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>\n\
                            <td><input type="text" name="observ[]" class="form-control text-uppercase text-center" maxlength="255"></td>\n\
                            <td><input type="text" name="ingreso[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>\n\
                            <td><input type="text" name="salida[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>\n\
                            <td><input type="text" name="stop[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>\n\
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
            '<td><input type="date" name="fecha[]" class="form-control text-uppercase text-center"></td>'+
            '<td><input type="hidden" name="contador[]"><input type="hidden" name="estacion[]" id="hiddenestacion_'+cont+'"><input type="text" id="estacion_'+cont+'" class="form-control text-uppercase text-center"></td>'+
            '<td><input type="hidden" name="conductor[]" id="hiddenconductor_'+cont+'"><input type="text" id="conductor_'+cont+'" class="form-control conductor text-uppercase text-center"></td>'+
            '<td><input type="hidden" name="piloto[]" id="hiddenpiloto_'+cont+'"><input type="text" id="piloto_'+cont+'" class="form-control piloto text-uppercase text-center"></td>'+
            '<td><input type="text" name="km[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="6"></td>'+
            '<td><input type="text" name="xtanque[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>'+
            '<td><input type="text" name="qabast[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>'+
            '<td><input type="text" name="observ[]" class="form-control text-uppercase text-center" maxlength="255"></td>'+
            '<td><input type="text" name="ingreso[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>'+
            '<td><input type="text" name="salida[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>'+
            '<td><input type="text" name="stop[]" class="form-control text-uppercase text-center" onkeypress="return soloNumeroTab(event);" maxlength="8"></td>'+
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

jQuery(document).on("click", "#btn_act_tblconsumos", function(){
    $("#txt_buscar_nrovale").val('');
    $("#txt_buscar_placa").val('');
    $("#txt_buscar_fdesde").val('');
    $("#txt_buscar_fhasta").val('');
    
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
            $("#txt_cde_qabast").val(data.cde_qabastecida);
            $("#txt_cde_observaciones").val(data.cde_observaciones);
            $("#txt_cde_ingreso").val(data.cde_ingreso);
            $("#txt_cde_salida").val(data.cde_salida);
            $("#txt_cde_stop").val(data.cde_stop);
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
            capacidad:$('#cbx_capacidad').val()
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

jQuery(document).on("click", "#btn_vw_buscar_consumos", function(){
    jQuery("#tblconsumosdet").jqGrid('setGridParam', {
        url: 'consumo/0?grid=consumos&indice=1&nrovale='+$("#txt_buscar_nrovale").val()+'&placa='+$("#txt_buscar_placa").val()+'&fdesde='+$("#txt_buscar_fdesde").val()+'&fhasta='+$("#txt_buscar_fhasta").val()
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
            if (data.rut_descripcion == 'OR') 
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
                mostraralertasconfoco('EL NUMERO DE VALE NO ES "OR" ');
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
            capacidad:$('#cbx_capacidad').val()
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