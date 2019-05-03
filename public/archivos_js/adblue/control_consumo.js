jQuery(document).ready(function ($) {
    $('#men_registro').addClass('menu-open');
    $('a[href*="#1"]').addClass('active');
    $('.control_consumo').addClass('active');
    
    miFechaActual = new Date();
    mes = parseInt(miFechaActual.getMonth()) + 1;
    anio = miFechaActual.getFullYear();
    $("#cbx_cde_mes").val(mes);
    $("#cbx_cde_anio").val(anio);
    
    $("#cbx_dat_mes").val(mes);
    $("#cbx_dat_anio").val(anio);
    
    $("#cbx_cost_mes").val(mes);
    $("#cbx_cost_anio").val(anio);

    jQuery("#tblcontrol_consumo").jqGrid({
        url: 'control_consumo/0?grid=control_consumo&mes='+mes+'&anio='+anio,
        datatype: 'json', mtype: 'GET',
        height: '340px', autowidth: true,
        toolbarfilter: true,
        sortable: false,
        shrinkToFit: false,
        forceFit:true,  
        scroll: false,
        colNames: ['CCA_ID', 'CDE_ID', 'FECHA', 'PLACA', 'CONDUCTOR', 'COPILOTO', 'RUTA', 'Q - ABAST.', 'TOTAL CONSUMO REAL', 'TOTAL CONSUMO DESEADO', 'AH. - EX. CONSUMO TOTAL','MONTO OPTIMO ABASTECER','AHORRO EXCESO GRAL','AHORRO POR VIAJE','EXCESO POR VIAJE','KM.I','KM.F','KILOMETRAJE','RENDIMIENTO KM/LT','RENDIMIENTO KM/GL'],
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
            {name: 'xcde_rendimiento_lt', index: 'xcde_rendimiento_lt', align: 'center', width: 100},
            {name: 'xcde_rendimiento_gl', index: 'xcde_rendimiento_gl', align: 'center', width: 100},
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
            { "numberOfColumns": 5, "titleText": "<center><h5>KILOMETRAJES</h5></center>", "startColumnName": "xcde_kmi",align: 'center' }]
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
    
    //DATOS PROMEDIOS GENERALES RUTAS
    
    jQuery("#tblprom_gen_rut").jqGrid({
        url: 'control_consumo/0?grid=prom_gen_rut&mes='+mes+'&anio='+anio,
        datatype: 'json', mtype: 'GET',
        height: 'auto', autowidth: true,
        cmTemplate: { sortable: false },
        colNames: ['RUTA', 'CONSUMO','KILOMETRAJE','RENDIMIENTO','AHORRO','EXCESO','TOTAL A/E','N° VIAJES'],
        rowNum: 10, sortname: 'xcde_ruta', sortorder: 'asc', align: "center",
        colModel: [
            {name: 'xcde_ruta', index: 'xcde_ruta', align: 'left',width:150,frozen:true},
            {name: 'consumo', index: 'consumo', align: 'center', width: 170},
            {name: 'kg', index: 'kg', align: 'center', width: 170},
            {name: 'rendimiento', index: 'rendimiento', align: 'center', width: 170},
            {name: 'ahorro', index: 'ahorro', align: 'center', width: 170},
            {name: 'exceso', index: 'exceso', align: 'center', width: 170},
            {name: 'totalae', index: 'totalae', align: 'center', width: 170},
            {name: 'nro_viajes', index: 'nro_viajes', align: 'center', width: 100}
        ],
        rownumbers: true,
        rownumWidth: 25,
        loadComplete: function () {
            var sum = jQuery("#tblprom_gen_rut").getGridParam('userData').sum;
            if(sum==undefined){
                $("#total_viajes_rut").val('0');
            }else{
                $("#total_viajes_rut").val(sum);
            }
        },
        pager: '#paginador_tblprom_gen_rut',
        rowList: [],       
        pgbuttons: false,     
        pgtext: null,       
        viewrecords: false
    });
    
    $('#tblprom_gen_rut').setGroupHeaders(
    {
        useColSpanStyle: true,
        groupHeaders: [
            { "numberOfColumns": 7, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>DATOS PROMEDIOS GENERALES POR TODA LA RUTA</h5></div><div class='col-md-4'><button class='btn btn-warning' onClick='traer_datos_desplegable(tblprom_gen_rut,1);'><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "consumo",align: 'center'}]
    });
    
    //DATOS GENERALES POR PLACA - SCANIA
    
    jQuery("#tblgen_scania").jqGrid({
        url: 'control_consumo/0?grid=dat_gen_escania&mes='+mes+'&anio='+anio,
        datatype: 'json', mtype: 'GET',
        height: 'auto', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        cmTemplate: { sortable: false },
        colNames: ['SCANIA', 'RENDIMIENTO','AHORRO','EXCESO','TOTAL A/E','N° VIAJES'],
        rowNum: 10, sortname: 'xcde_placa', sortorder: 'asc', viewrecords: false, align: "center",
        colModel: [
            {name: 'xcde_placa', index: 'xcde_placa', align: 'left',width: 100,frozen:true},
            {name: 'rendimiento', index: 'rendimiento', align: 'center', width: 120},
            {name: 'ahorro', index: 'ahorro', align: 'center', width: 100},
            {name: 'exceso', index: 'exceso', align: 'center', width: 100},
            {name: 'totalae', index: 'totalae', align: 'center', width: 100},
            {name: 'nro_viajes', index: 'nro_viajes', align: 'center', width: 100}
        ],
        rownumbers: true, // show row numbers
        rownumWidth: 25, // the width of the row numbers columns
        loadComplete: function () {
            var sum = jQuery("#tblgen_scania").getGridParam('userData').sum;
            if(sum==undefined){
                $("#total_viajes_scania").val('0');
            }else{
                $("#total_viajes_scania").val(sum);
            }
        },
        pager: '#paginador_tblgen_scania',
        rowList: [],       
        pgbuttons: false,     
        pgtext: null,       
    });
    
    $('#tblgen_scania').setGroupHeaders(
    {
        useColSpanStyle: true,
        groupHeaders: [
            { "numberOfColumns": 5, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>DATOS GENERALES POR PLACA</h5></div><div class='col-md-4'><button class='btn btn-warning' onClick='traer_datos_desplegable(tblgen_scania,2);'><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "rendimiento",align: 'center'}]
    });
    
    //DATOS GENERALES POR PLACA - IRIZAR
    
    jQuery("#tblprom_gen_irizar").jqGrid({
        url: 'control_consumo/0?grid=dat_gen_irizar&mes='+mes+'&anio='+anio,
        datatype: 'json', mtype: 'GET',
        height: 'auto', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        cmTemplate: { sortable: false },
        colNames: ['IRIZAR', 'RENDIMIENTO','AHORRO','EXCESO','TOTAL A/E','N° VIAJES'],
        rowNum: 10, sortname: 'xcde_placa', sortorder: 'asc', viewrecords: false, align: "center",
        colModel: [
            {name: 'xcde_placa', index: 'xcde_placa', align: 'left',width: 100,frozen:true},
            {name: 'rendimiento', index: 'rendimiento', align: 'center', width: 120},
            {name: 'ahorro', index: 'ahorro', align: 'center', width: 100},
            {name: 'exceso', index: 'exceso', align: 'center', width: 100},
            {name: 'totalae', index: 'totalae', align: 'center', width: 100},
            {name: 'nro_viajes', index: 'nro_viajes', align: 'center', width: 100}
        ],
        rownumbers: true, // show row numbers
        rownumWidth: 25, // the width of the row numbers columns
        loadComplete: function () {
            var sum = jQuery("#tblprom_gen_irizar").getGridParam('userData').sum;
            if(sum==undefined){
                $("#total_viajes_irizar").val('0');
            }else{
                $("#total_viajes_irizar").val(sum);
            }
        },
        pager: '#paginador_tblprom_gen_irizar',
        rowList: [],       
        pgbuttons: false,     
        pgtext: null
    });
    
    $('#tblprom_gen_irizar').setGroupHeaders(
    {
        useColSpanStyle: true,
        groupHeaders: [
            { "numberOfColumns": 5, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>DATOS GENERALES POR PLACA</h5></div><div class='col-md-4'><button class='btn btn-warning' onClick='traer_datos_desplegable(tblprom_gen_irizar,3);'><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "rendimiento",align: 'center'}]
    });
    
    
    //COSTO OPTIMO GENERAL POR ABASTECIMIENTO EN RUTA
    
    jQuery("#tblcost_opt_ruta").jqGrid({
        url: 'control_consumo/0?grid=cost_opt_ruta&mes='+mes+'&anio='+anio,
        datatype: 'json', mtype: 'GET',
        height: 'auto', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        cmTemplate: { sortable: false },
        colNames: ['RUTA', 'CONSUMO DESEADO GENERAL','CONSUMO REAL AREQUIPA','TOTAL A/E','COSTO CDG','COSTO CRA','COSTO A/E'],
        rowNum: 10, sortname: 'xrut_id', sortorder: 'asc', viewrecords: false, align: "center",
        colModel: [
            {name: 'xcde_ruta', index: 'xcde_ruta', align: 'left',width: 150,frozen:true},
            {name: 'cdg', index: 'cdg', align: 'center', width: 200},
            {name: 'cra', index: 'cra', align: 'center', width: 200},
            {name: 'totalae', index: 'totalae', align: 'center', width: 160},
            {name: 'costocdg', index: 'costocdg', align: 'center', width: 160,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cdg'},
            {name: 'costocra', index: 'costocra', align: 'center', width: 160,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cra'},
            {name: 'costoae', index: 'costoae', align: 'center', width: 160,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_ae'}
        ],
        rownumbers: true, // show row numbers
        rownumWidth: 25, // the width of the row numbers columns
        loadComplete: function () {
            var $self = $(this);
            var total_costocdg = $self.jqGrid("getCol", "costocdg", false, "sum");
            var total_costocra = $self.jqGrid("getCol", "costocra", false, "sum");
            var total_costoae = $self.jqGrid("getCol", "costoae", false, "sum");
            $("#total_cdg").val("S/."+ Number(total_costocdg.toFixed(3)));
            $("#total_cra").val("S/."+ Number(total_costocra.toFixed(3)));
            $("#total_cae").val("S/."+ Number(total_costoae.toFixed(3)));
        },
        pager: '#paginador_tblcost_opt_ruta',
        rowList: [],       
        pgbuttons: false,     
        pgtext: null
    });
    
    $('#tblcost_opt_ruta').setGroupHeaders(
    {
        useColSpanStyle: true,
        groupHeaders: [
            { "numberOfColumns": 6, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>COSTO OPTIMO GENERAL POR ABASTECIMIENTO EN  RUTA</h5></div><div class='col-md-4'><button class='btn btn-warning' onClick='traer_datos_desplegable(tblcost_opt_ruta,4);'><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "cdg",align: 'center'}]
    });
    
    //COSTO GENERAL  POR ABASTECIMIENTO EN RUTA
    
    jQuery("#tblcost_gen_abast_ruta").jqGrid({
        url: 'control_consumo/0?grid=cost_gen_abast_ruta&mes='+mes+'&anio='+anio,
        datatype: 'json', mtype: 'GET',
        height: 'auto', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        cmTemplate: { sortable: false },
        colNames: ['RUTA', 'AHORRO','TOTAL C/A','EXCESO','TOTAL C/E','TOTAL C/AE'],
        rowNum: 10, sortname: 'xcde_ruta', sortorder: 'asc', viewrecords: false, align: "center",
        colModel: [
            {name: 'xcde_ruta', index: 'xcde_ruta', align: 'left',width: 200,frozen:true},
            {name: 'ahorro', index: 'ahorro', align: 'center', width: 200},
            {name: 'totalca', index: 'totalca', align: 'center', width: 200,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cdg'},
            {name: 'exceso', index: 'exceso', align: 'center', width: 200},
            {name: 'totalce', index: 'totalce', align: 'center', width: 200,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cra'},
            {name: 'totalcae', index: 'totalcae', align: 'center', width: 200,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_ae'}
        ],
        rownumbers: true,
        rownumWidth: 25, 
        loadComplete: function () {
            var $self = $(this);
            var total_cta = $self.jqGrid("getCol", "totalca", false, "sum");
            var total_cte = $self.jqGrid("getCol", "totalce", false, "sum");
            var total_ctae = $self.jqGrid("getCol", "totalcae", false, "sum");
            $("#total_cta").val("S/."+ Number(total_cta.toFixed(3)));
            $("#total_cte").val("S/."+ Number(total_cte.toFixed(3)));
            $("#total_ctae").val("S/."+ Number(total_ctae.toFixed(3)));
        },
        pager: '#paginador_tblcost_gen_abast_ruta',
        rowList: [],       
        pgbuttons: false,     
        pgtext: null
    });
    
    $('#tblcost_gen_abast_ruta').setGroupHeaders(
    {
        useColSpanStyle: true,
        groupHeaders: [
            { "numberOfColumns": 5, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>COSTO GENERAL  POR ABASTECIMIENTO EN RUTA</h5></div><div class='col-md-4'><button class='btn btn-warning' onClick='traer_datos_desplegable(tblcost_gen_abast_ruta,5);'><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "ahorro",align: 'center'}]
    });
    
    //COSTO GENERAL POR ABASTECIMIENTO POR PLACA
    
    jQuery("#tblcost_gen_abast_placa").jqGrid({
        url: 'control_consumo/0?grid=cost_gen_abast_placa&mes='+mes+'&anio='+anio,
        datatype: 'json', mtype: 'GET',
        height: 'auto', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        cmTemplate: { sortable: false },
        colNames: ['PLACA', 'AHORRO','COSTO AHORRO','EXCESO','COSTO EXCESO'],
        rowNum: 10, sortname: 'xcde_placa', sortorder: 'asc', viewrecords: false, align: "center",
        colModel: [
            {name: 'xcde_placa', index: 'xcde_placa', align: 'left',width: 200,frozen:true},
            {name: 'ahorro', index: 'ahorro', align: 'center', width: 250},
            {name: 'costo_ahorro', index: 'costo_ahorro', align: 'center', width: 250,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cdg'},
            {name: 'exceso', index: 'exceso', align: 'center', width: 250,formatter: FormatoNumeros},
            {name: 'costo_exceso', index: 'costo_exceso', align: 'center', width: 250,formatter: 'currency',formatoptions: {decimalPlaces: 3, prefix: 'S/.' },classes: 'costo_cra'}
        ],
        rownumbers: true,
        rownumWidth: 25, 
        loadComplete: function () {
            var $self = $(this);
            var total_cta_placa = $self.jqGrid("getCol", "costo_ahorro", false, "sum");
            var total_cte_placa = $self.jqGrid("getCol", "costo_exceso", false, "sum");
            var total = total_cta_placa + total_cte_placa;
            $("#total_cta_placa").val("S/."+ Number(total_cta_placa.toFixed(3)));
            $("#total_cte_placa").val("S/."+ Number(total_cte_placa.toFixed(3)));
            $("#total_ctae_placa").val("S/."+ Number(total.toFixed(3)));
        },
        pager: '#paginador_tblcost_gen_abast_placa',
        rowList: [],       
        pgbuttons: false,     
        pgtext: null
    });
    
    $('#tblcost_gen_abast_placa').setGroupHeaders(
    {
        useColSpanStyle: true,
        groupHeaders: [
            { "numberOfColumns": 5, "titleText": "<div class='row text-center'><div class='col-md-8'><h5>COSTO GENERAL POR ABASTECIMIENTO POR PLACA</h5></div><div class='col-md-4'><button class='btn btn-warning' onClick='traer_datos_desplegable(tblcost_gen_abast_placa,6);'><i class='fa fa-print'></i> IMPRIMIR</button</div></div>", "startColumnName": "ahorro",align: 'center'}]
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
        cmTemplate: { sortable: false },
        sortname: 'cde_id', sortorder: 'asc', viewrecords: true,
        caption: '<div class="row"><div class="col-md-2">- ESTACION CONSUMO - </div><div class="col-md-9" id=cabecera_'+childGridID+'></div></div>', align: "center",
        colModel: [
            {name: 'cde_id', index: 'cde_id', align: 'left',width: 10, hidden:true},
            {name: 'est_descripcion', index: 'est_descripcion', align: 'left', width: 60},
            {name: 'cde_qabastecida', index: 'cde_qabastecida', align: 'center', width: 20}
        ],
        loadonce: true,
        width: 1300,
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