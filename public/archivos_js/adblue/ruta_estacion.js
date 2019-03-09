jQuery(document).ready(function($){
    $('#men_registro').addClass('menu-open');
    $('a[href*="#1"]').addClass('active');
    $('.ruta_estacion').addClass('active');
    
    mostrarformulario(false);
    
    jQuery("#tblrutaestacion").jqGrid({
        url: 'ruta_estacion/0?grid=rutas',
        datatype: 'json', mtype: 'GET',
        height: '550px', autowidth: true,
        colNames: ['ID', 'DESCRIPCION','FECHA REGISTRO'],
        rowNum: 100, sortname: 'rut_id', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblruta" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE RUTAS -', align: "center",
        colModel: [
            {name: 'rut_id', index: 'rut_id', align: 'left',width: 10, hidden:true},
            {name: 'rut_descripcion', index: 'rut_descripcion', align: 'left', width: 60},
            {name: 'rut_fecregistro', index: 'rut_fecregistro', align: 'center', width: 25}
        ],
        loadonce : true,
        subGrid: true,
        subGridRowExpanded: showChildGrid,
        subGridOptions : {
            reloadOnExpand :false,
            selectOnExpand : true 
        },
        gridComplete: function () {
            var idarray = jQuery('#tblrutaestacion').jqGrid('getDataIDs');
            if (idarray.length > 0) {
            var firstid = jQuery('#tblrutaestacion').jqGrid('getDataIDs')[0];
                $("#tblrutaestacion").setSelection(firstid);    
            }
        },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){$("#btn_vw_rtestacion_Modificar").click();}
    });
    
    $(window).on('resize.jqGrid', function () {
        $("#tblrutaestacion").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
    jQuery.fn.preventDoubleSubmission = function() {
        cambiar_estado_estacion();
    };
    
    $(".select2").select2();
});

function showChildGrid(parentRowID, parentRowKey) {
    var childGridID = parentRowID + "_table";
    var childGridPagerID = parentRowID + "_pager";

    $('#' + parentRowID).append('<table id=' + childGridID + '></table><div id=' + childGridPagerID + ' class=scroll></div>');

    $("#" + childGridID).jqGrid({
        url: 'ruta_estacion/0?grid=ruta_estaciones&rut_id='+parentRowKey,
        mtype: "GET",
        datatype: "json",
        page: 1,
        colNames: ['ID', 'ESTACION','CONSUMO','FECHA REGISTRO','AÑO','ESTADO'],
        colModel: [
            {name: 'rte_id', index: 'rte_id', align: 'left',width: 10, hidden:true},
            {name: 'est_descripcion', index: 'est_descripcion', align: 'left', width: 60},
            {name: 'rte_consumo', index: 'rte_consumo', align: 'center', width: 15},
            {name: 'rte_fecregistro', index: 'rte_fecregistro', align: 'center', width: 12},
            {name: 'rte_anio', index: 'rte_anio', align: 'center', width: 10},
            {name: 'rte_estado', index: 'rte_estado', align: 'center', width: 10}
        ],
        loadonce: true,
        autowidth: true,
        height: '100%',
        ondblClickRow: function (Id){modificar_rtestacion(Id);}
    });
}

function mostrarformulario(flag)
{
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
        $("#formularioButtonsEditar").hide();
        detalles=0;
    }
}

jQuery(document).on("click", "#btn_vw_rtestacion_Cancelar", function(){
    $(".filas").remove();
    $("#btn_vw_rtestacion_Guardar").hide();
    mostrarformulario(false);
});

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

jQuery(document).on("click", "#btn_vw_rtestacion_Nuevo", function(){
    rut_id = $('#tblrutaestacion').jqGrid ('getGridParam', 'selrow');
    if (rut_id) 
    {
        ruta = $('#tblrutaestacion').jqGrid ('getCell', rut_id, 'rut_descripcion');
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'ruta_estacion/'+rut_id+'?show=datos',
            type: 'GET',
            beforeSend:function()
            {            
                MensajeEspera('RECUPERANDO INFORMACION');  
            },
            success: function(data) 
            {
                html = "";
                if (data == 0) 
                {
                    $(".filas").remove();
                }
                else
                {
                    for(i=0;i<data.length;i++)
                    {
                        html = html+'<tr class="filas" id="fila">\n\
                            <td></td>\n\
                            <td><input type="hidden" name="nro_filas_c[]"><input type="hidden" name="estaciones_c[]" id="hiddenestacion_' + data[i].rte_id + '"><input type="text" id="estacion_' + data[i].rte_id + '" disabled="" class="form-control text-uppercase text-center" value="'+data[i].est_descripcion+'"></td>\n\
                            <td><input type="text" name="consumo_c[]" value="'+data[i].rte_consumo+'" class="form-control text-center" disabled="" onkeypress="return soloNumeroTab(event);"></td>\n\
                            </tr>';
                        $("#cuerpo_rtestaciones").html(html);
                    }
                }
                $("#lbl_rte_ruta_create").attr('id_ruta',rut_id);
                $("#lbl_rte_ruta_create").html('<b>RUTA SELECCIONADA: '+ruta+'</b>');
                swal.close();
                mostrarformulario(true);
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
        mostraralertasconfoco("NO HAY RUTAS SELECCIONADAS","#tblrutaestacion");
    }
});

var cont=0;
var detalles=0;
$("#btn_vw_rtestacion_Guardar").hide();
jQuery(document).on("click", "#btn_generar_estaciones", function(){
    var cantidad=1;
    var fila='<tr class="filas" id="fila'+cont+'">'+
    '<td><button type="button" class="btn btn-danger btn-xl" onclick="eliminarDetalle('+cont+')">X</button></td>'+
    '<td><input type="hidden" name="nro_filas[]"><input type="hidden" name="estaciones[]" id="hiddenestacion_' + cont + '"><input type="text" id="estacion_' + cont + '" class="form-control text-uppercase text-center"></td>'+
    '<td><input type="text" name="consumo[]" value="'+cantidad+'" class="form-control text-center" onkeypress="return soloNumeroTab(event);"></td>'+
    '</tr>';
    autocompletar_estaciones('estacion_'+cont);
    cont++;
    detalles=detalles+1;
    $('#cuerpo_rtestaciones').append(fila);
    evaluar();
});

function evaluar()
{
    if (detalles>0)
    {
        $("#btn_vw_rtestacion_Guardar").show();
    }
    else
    {
        $("#btn_vw_rtestacion_Guardar").hide();
        cont=0;
    }
}
 
function eliminarDetalle(indice)
{
    $("#fila" + indice).remove();
    detalles=detalles-1;
    cont++;
    evaluar();
}

jQuery(document).on("click", "#btn_vw_rtestacion_Guardar", function(){
    var form = new FormData($("#FormularioRtesaciones")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'ruta_estacion?tipo=1&id_ruta='+$("#lbl_rte_ruta_create").attr('id_ruta'),
        type: 'POST',
        dataType: 'json',
        data: form,
        processData: false,
        contentType: false,
        success: function (data) 
        {
            if (data == 1) 
            {
                MensajeConfirmacion('EL RESPUESTA FUE ENVIADA CON EXITO');
                jQuery("#tblrutaestacion").jqGrid('setGridParam', {
                    url: 'ruta_estacion/0?grid=rutas'
                }).trigger('reloadGrid');
                $(".filas").remove();
                $("#btn_vw_rtestacion_Guardar").hide();
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

jQuery(document).on("click", "#btn_act_tblruta", function(){
    jQuery("#tblrutaestacion").jqGrid('setGridParam', {
        url: 'ruta_estacion/0?grid=rutas'
    }).trigger('reloadGrid');
});

function modificar_rtestacion(rte_id)
{
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'ruta_estacion/'+rte_id+'?show=estaciones',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION');  
        },
        success: function(data) 
        {
            Estaciones = $('#ModalRutaEstacion').modal({backdrop: 'static', keyboard: false});
            Estaciones.find('.modal-title').text('EDITAR ESTACION');
            Estaciones.find('#btn_actualizar_rtestacion').html('<i class="fa fa-pencil-square-o"></i> MODIFICAR').show();
            
            $("#lbl_rte_ruta").attr('rte_id',data[0].rte_id);
            $("#lbl_rte_ruta").html("RUTA: " + "<b>"+data[0].rut_descripcion+"</b>");
            $("#txt_rte_consumo").val(data[0].rte_consumo);
            $("#txt_rte_estacion").val(data[0].est_id);
            $("#txt_rte_estacion").change();   
            swal.close();
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

jQuery(document).on("click", "#btn_actualizar_rtestacion", function(){
    if ($('#txt_rte_consumo').val() == '') {
        mostraralertasconfoco('* EL CAMPO CONSUMO ES OBLIGATORIO...', '#txt_rte_consumo');
        return false;
    }
    $.ajax({
        url: 'ruta_estacion/'+$("#lbl_rte_ruta").attr('rte_id')+'/edit',
        type: 'GET',
        data:
        {
            estacion:$('#txt_rte_estacion').val(),
            consumo:$('#txt_rte_consumo').val(),
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
                jQuery("#tblrutaestacion").jqGrid('setGridParam', {
                    url: 'ruta_estacion/0?grid=rutas'
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

function cambiar_est_rte(rte_id,rut_id,estado)
{
    $.ajax({
        url: 'ruta_estacion/'+rte_id+'/edit',
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
                jQuery("#tblrutaestacion_"+rut_id).jqGrid('setGridParam', {
                    url: 'ruta_estacion/0?grid=ruta_estaciones&rut_id='+rut_id
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