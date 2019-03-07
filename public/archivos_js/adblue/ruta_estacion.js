jQuery(document).ready(function($){
    $('#men_registro').addClass('menu-open');
    $('a[href*="#1"]').addClass('active');
    $('.ruta_estacion').addClass('active');
    
    mostrarformulario(false);
    
    jQuery("#tblrutaestacion").jqGrid({
        url: 'ruta_estacion/0?grid=rutas',
        datatype: 'json', mtype: 'GET',
        height: '470px', autowidth: true,
        colNames: ['ID', 'DESCRIPCION','FECHA REGISTRO','ESTADO'],
        rowNum: 100, sortname: 'rut_id', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblruta" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE RUTAS -', align: "center",
        colModel: [
            {name: 'rut_id', index: 'rut_id', align: 'left',width: 10, hidden:true},
            {name: 'rut_descripcion', index: 'rut_descripcion', align: 'left', width: 60},
            {name: 'rut_fecregistro', index: 'rut_fecregistro', align: 'center', width: 15},
            {name: 'rut_estado', index: 'rut_estado', align: 'center', width: 10}
        ],
        loadonce : true,
        subGrid: true,
        subGridRowExpanded: showChildGrid,
        subGridOptions : {
                reloadOnExpand :false,
                selectOnExpand : true 
            },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){$('#btn_vw_rtestacion_Editar').click();}
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
    });

}

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
        $("#formularioButtonsEditar").hide();
    }
}

jQuery(document).on("click", "#btn_vw_equipos_Cancelar", function(){
    //limpiar_datos();
    mostrarformulario(false);
})

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
    $('#detalles').append(fila);
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
    //item_retirado();
}

jQuery(document).on("click", "#btn_vw_rtestacion_Guardar", function() {
    var form = new FormData($("#FormularioRtesaciones")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'ruta_estacion?tipo=1',
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

jQuery(document).on("click", "#btn_vw_rtestacion_Editar", function(){
    rut_id = $('#tblrutaestacion').jqGrid ('getGridParam', 'selrow');
    if(rut_id){
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
                html="";
                if (data == 0) 
                {
                    mostraralertasconfoco("LA RUTA NO TIENE ESTACIONES ASIGNADAS");
                }
                else
                {
                    $("#listadoRegistros").hide();
                    $("#formularioRegistros").show();
                    $("#listadoButtons").hide();
                    $("#formularioButtonsEditar").show();
                    
                    //$("#cbx_rutas").text('teto');
                    $("#cbx_rutas").val(rut_id);
                    $("#cbx_rutas").attr('disabled',true);
                    
                    for(i=0;i<data.length;i++)
                    {
                        html = html+'<tr class="filas" id="fila">\n\
                            <td><button type="button" class="btn btn-danger btn-xl" onclick="eliminar('+data[i].rte_id+')">X</button></td>\n\
                            <td><input type="hidden" name="nro_filas[]"><input type="hidden" name="estaciones[]" id="hiddenestacion_' + data[i].rte_id + '"><input type="text" id="estacion_' + data[i].rte_id + '" disabled="" class="form-control text-uppercase text-center" value="'+data[i].est_descripcion+'"></td>\n\
                            <td><input type="text" name="consumo[]" value="'+data[i].rte_consumo+'" class="form-control text-center" onkeypress="return soloNumeroTab(event);"></td>\n\
                            </tr>';
                        $("#detalles").html(html);
                    }
                    swal.close();
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
    else{
        mostraralertasconfoco("NO HAY RUTAS SELECCIONADAS","#tblrutaestacion");
    }
});
