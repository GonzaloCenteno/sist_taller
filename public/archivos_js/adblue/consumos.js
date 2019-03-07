jQuery(document).ready(function($){
    $('#men_registro').addClass('menu-open');
    $('a[href*="#1"]').addClass('active');
    $('.consumo').addClass('active');
    
    mostrarformulario(false);
    
    jQuery("#tblconsumosdet").jqGrid({
        url: 'consumo/0?grid=consumos',
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
        ondblClickRow: function (Id){$('#btn_modificar_consumocab').click();}
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
    
    jQuery.fn.preventDoubleSubmission = function() {
        cambiar_estado_estacion();
    };
    
    $(".select2").select2();
});

//function limpiar_datosEstacion()
//{
//    $('#txt_est_descripcion').val('');
//}

function mostrarformulario(flag)
{
    //limpiar_datos();
    if (flag)
    {
        $("#listadoRegistros").hide();
        $("#formularioRegistros").show();
        $("#listadoButtons").hide();
        $("#formularioButtons").show();
        $("#form_codigo").focus();
    }
    else
    {
        $("#listadoRegistros").show();
        $("#formularioRegistros").hide();
        $("#listadoButtons").show();
        $("#formularioButtons").hide();
        $("#btn_vw_consumoscab_Guardar").attr('disabled',true);
    }
}

jQuery(document).on("click", "#btn_vw_consumoscab_Cancelar", function(){
    //limpiar_datos();
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

$("#btn_vw_rtestacion_Guardar").hide();
jQuery(document).on("click", "#btn_generar_consumodet", function(){
    $("#btn_vw_consumoscab_Guardar").removeAttr('disabled',true);
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
            
            if (data[0].rut_descripcion == 'OR') 
            {
                alert('otras rutas');     
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
                                    <th style="width: 10%;">INGRESO</th>\n\
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
    jQuery("#tblconsumosdet").jqGrid('setGridParam', {
        url: 'consumo/0?grid=consumos'
    }).trigger('reloadGrid');
});

jQuery(document).on("click", "#btn_modificar_consumocab", function(){
    cde_id = $('#tblconsumosdet').jqGrid ('getGridParam', 'selrow');
    if(cde_id){
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
    else{
        mostraralertasconfoco("NO HAY CONSUMOS SELECCIONADOS","#tblconsumosdet");
    }
});

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