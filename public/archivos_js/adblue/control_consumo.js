jQuery(document).ready(function ($) {
    $('#men_registro').addClass('menu-open');
    $('a[href*="#1"]').addClass('active');
    $('.control_consumo').addClass('active');
    
    miFechaActual = new Date();
    mes = parseInt(miFechaActual.getMonth()) + 1;
    anio = miFechaActual.getFullYear();
    $("#cbx_cde_mes").val(mes);
    $("#cbx_cde_anio").val(anio);

    jQuery("#tblcontrol_consumo").jqGrid({
        url: 'control_consumo/0?grid=control_consumo&mes='+mes+'&anio='+anio,
        datatype: 'json', mtype: 'GET',
        height: '500px', autowidth: true,
        toolbarfilter: true,
        sortable: false,
        shrinkToFit: false,
        forceFit:true,  
        scroll: false,
        colNames: ['CCA_ID', 'CDE_ID', 'FECHA', 'PLACA', 'CONDUCTOR', 'COPILOTO', 'RUTA', 'Q - ABAST.', 'CONSUMO REAL', 'CONSUMO DESEADO', 'AHORRO - EXCESO','MONTO OPTIMO','AHORRO EXCESO GRAL','AHORRO POR VIAJE','EXCESO POR VIAJE','KM.I','KM.F','KILOMETRAJE','RENDIMIENTO'],
        rowNum: 20, sortname: 'xcde_placa', sortorder: 'asc', viewrecords: true, caption: '<button id="btn_act_tblcontrol_consumo" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - CONTROL CONSUMO AREQUIPA -', align: "center",
        colModel: [
            {name: 'xcca_id', index: 'xcca_id', align: 'left', width: 10, hidden: true,frozen:true},
            {name: 'xcde_id', index: 'xcde_id', align: 'left', width: 10, hidden: true,frozen:true},
            {name: 'xcde_fecha', index: 'xcde_fecha', align: 'center', width: 100, formatter: 'date', formatoptions: {srcformat: 'Y-m-d', newformat: 'd/m'},frozen:true},
            {name: 'xcde_placa', index: 'xcde_placa', align: 'center', width: 100,frozen:true},
            {name: 'xcde_conductor', index: 'xcde_conductor', align: 'left', width: 250},
            {name: 'xcde_copiloto', index: 'xcde_copiloto', align: 'left', width: 250},
            {name: 'xcde_ruta', index: 'xcde_ruta', align: 'center', width: 150},
            {name: 'xcde_qabastecida', index: 'xcde_qabastecida', align: 'center', width: 150},
            {name: 'xcde_consumo_real', index: 'xcde_consumo_real', align: 'center', width: 100},
            {name: 'xcde_consumo_deseado', index: 'xcde_consumo_deseado', align: 'center', width: 100},
            {name: 'xcde_ahorro_exceso_com', index: 'xcde_ahorro_exceso_com', align: 'center', width: 100,formatter: FormatoNumeros},
            {name: 'xcde_montoptimo', index: 'xcde_montoptimo', align: 'center', width: 100},
            {name: 'xcde_ahex_gral', index: 'xcde_ahex_gral', align: 'center', width: 100},
            {name: 'xcde_ahxviaje', index: 'xcde_ahxviaje', align: 'center', width: 100},
            {name: 'xcde_exxviaje', index: 'xcde_exxviaje', align: 'center', width: 100,formatter: FormatoNumeros},
            {name: 'xcde_kmi', index: 'xcde_kmi', align: 'center', width: 100},
            {name: 'xcde_kmf', index: 'xcde_kmf', align: 'center', width: 100},
            {name: 'xcde_kilometraje', index: 'xcde_kilometraje', align: 'center', width: 100},
            {name: 'xcde_rendimiento', index: 'xcde_rendimiento', align: 'center', width: 100}
        ],
        pager: '#paginador_tblcontrol_consumo',
        rowList: [10, 20, 30, 40, 50],
        subGrid: true,
        subGridRowExpanded: mostrar_estaciones,
        subGridOptions : {
            reloadOnExpand :false,
            selectOnExpand : true 
        },
        gridComplete: function () {
            var idarray = jQuery('#tblcontrol_consumo').jqGrid('getDataIDs');
            if (idarray.length > 0) {
            var firstid = jQuery('#tblcontrol_consumo').jqGrid('getDataIDs')[0];
                $("#tblcontrol_consumo").setSelection(firstid);    
            }
        },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){}
    });
    
    $('#tblcontrol_consumo').setGroupHeaders(
    {
        useColSpanStyle: true,
        groupHeaders: [
            { "numberOfColumns": 4, "titleText": "<center><h5>RESUMEN</h5></center>", "startColumnName": "xcde_conductor",align: 'center'},
            { "numberOfColumns": 7, "titleText": "<center><h5>CONSUMOS, AHORROS - EXCESOS</h5></center>", "startColumnName": "xcde_consumo_real",align: 'center' },
            { "numberOfColumns": 4, "titleText": "<center><h5>KILOMETRAJES</h5></center>", "startColumnName": "xcde_kmi",align: 'center' }]
    });
    
    $("#tblcontrol_consumo").jqGrid("destroyFrozenColumns")
            .jqGrid("setColProp", "xcca_id", { frozen: true })
            .jqGrid("setColProp", "xcde_id", { frozen: true })
            .jqGrid("setColProp", "xcde_fecha", { frozen: true })
            .jqGrid("setColProp", "xcde_placa", { frozen: true })
            .jqGrid("setFrozenColumns")
            .trigger("reloadGrid", [{ current: true}]);
    
    $("#txt_cde_placa").keypress(function (e) {
        if (e.which == 13) {
            buscar_placas();
        }
    });
});

function FormatoNumeros(cellValue, options, rowObject) {
    var color = (parseInt(cellValue) < 0) ? "red" : "black";
    var cellHtml = "<span style='color:" + color + "' originalValue='" +
                         cellValue + "'>" + cellValue + "</span>";

    return cellHtml;
}

function mostrar_estaciones(parentRowID, parentRowKey) {
    var childGridID = parentRowID + "_table";
    var childGridPagerID = parentRowID + "_pager";

    $('#' + parentRowID).append('<table id=' + childGridID + '></table><div id=' + childGridPagerID + ' class=scroll></div>');

    $("#" + childGridID).jqGrid({
        url: 'control_consumo/0?grid=estaciones_consumo&cca_id='+parentRowKey,
        mtype: "GET",
        datatype: "json",
        page: 1,
        colNames: ['ID', 'ESTACION','Q - ABASTECIDA'],
        sortname: 'cde_id', sortorder: 'asc', viewrecords: true,
        caption: '<div class="row"><div class="col-md-2">- ESTACION CONSUMO - </div><div class="col-md-9" id=cabecera_'+childGridID+'></div></div>', align: "center",
        colModel: [
            {name: 'cco_id', index: 'cco_id', align: 'left',width: 10, hidden:true},
            {name: 'est_descripcion', index: 'est_descripcion', align: 'left', width: 60},
            {name: 'cde_qabastecida', index: 'cde_qabastecida', align: 'center', width: 20}
        ],
        loadonce: true,
        autowidth: true,
        height: '100%',
        loadComplete: function (data) {
            console.log(data);
            html = "";
            for(i=0;i < data.consumos.length; i++)
            {
                html = html + ' <label>'+data.consumos[i].est_descripcion+'</label> /';
                $("#cabecera_"+childGridID).html(html);
            }         
        },
        ondblClickRow: function (Id){modificar_consumo(Id);}
    });
}

jQuery(document).on("click", "#menu_push", function () {
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse'))
    {
        $("#tblcontrol_consumo").jqGrid('setGridWidth', 1520);
    } else
    {
        $("#tblcontrol_consumo").jqGrid('setGridWidth', 1327);
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
            Estaciones.find('.modal-title').text('EDITAR CONSUMO');
            Estaciones.find('#btn_actualizar_conest').html('<i class="fa fa-pencil-square-o"></i> MODIFICAR').show();
            
            $("#lbl_cde_consumo").attr('cde_id',data[0].cde_id);
            $("#lbl_cde_consumo").html("ESTACION: " + "<b>"+data[0].est_descripcion+"</b>");
            $("#txt_cde_qabastecida").val(data[0].cde_qabastecida); 
            setTimeout(function (){
                $('#txt_cde_qabastecida').focus();
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