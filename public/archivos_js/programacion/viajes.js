
jQuery(document).on("click", "#menu_push", function () {
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse'))
    {
        setTimeout(function () {
            var width = $('#contenedor').width();
            $('#tblviajes').setGridWidth(width);
        }, 300);
    } else
    {
        setTimeout(function () {
            var width = $('#contenedor').width();
            $('#tblviajes').setGridWidth(width);
        }, 300);
    }
});

function EstadoEstilo(cellValue, options, rowObject) {
    if(cellValue == false)
    {
        var opciones = '<button type="button" class="btn btn-xl btn-danger btn-round-animate"><i class="fa fa-times"></i></button>';
    }
    else
    {
        var opciones = '<button type="button" class="btn btn-xl btn-success btn-round-animate"><i class="fa fa-check"></i></button>';
    }
    return opciones;
}

function PlacaEstilo(cellValue, options, rowObject) {
    return '<font color="#000000" style="font-weight: bold;"><b>'+cellValue+'</b></font>';
}

function RutaEstilo(cellValue,options , rowObject) {
    return '<font style="font-weight: bold;">'+cellValue+'</font>';            
}

jQuery(document).on("click", "#btn_generar_cronograma", function() {
    if ($('#cbx_cantidad').val() == 0) {
        mostraralertasconfoco('* DEBE SELECCIONAR UNA CANTIDAD DE MESES...', '#cbx_cantidad');
        return false;
    }
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: 'viajes/0?generar=cronograma',
        data:{
            cantidad: $("#cbx_cantidad").val()
        },
        beforeSend:function()
        {            
            $('#bloque').block({ 
                message: '<h1><img src="img/img_cromotex/cargador_2.gif" />CARGANADO INFORMACION</h1>', 
                css: { border: '5px solid #a00',width: '350px' } 
            });
        },
        success: function(data) {
            //console.log(data[0].fn_generar_cronograma);
            if (data[0].fn_generar_cronograma_prueba === 1) 
            {
                jQuery("#tblviajes").jqGrid('setGridParam', {
                    url: 'viajes/0?grid=viajes'
                }).trigger('reloadGrid'); 
            }
            else
            {
                MensajeAdvertencia('OCURRIO UN PROBLEMA AL GENERAR EL CRONOGRAMA');
                console.log(data); 
            } 
            $("#cbx_cantidad").val(0);
            $('#bloque').unblock();                                                                
        },
        error: function(data) {
            MensajeAdvertencia("OCURRIO UN ERROR, COMUNICARSE CON EL ADMINISTRADOR");
            $('#bloque').unblock(); 
            console.log('error');
            console.log(data);
        }
    });
});

jQuery(document).on("click", "#btn_act_tblviajes", function() {
    jQuery("#tblviajes").jqGrid('setGridParam', {
        url: 'viajes/0?grid=viajes'
    }).trigger('reloadGrid');

    $("#txt_placa").val('');
    $("#cbx_cantidad").val(0);
});

$("#txt_placa").keypress(function (e) {
    if (e.which == 13) {
        buscar_placa();
    }
});

function buscar_placa()
{
    if ($('#txt_placa').val() == '') {
        mostraralertasconfoco('* ESTE CAMPO NO DEBE ESTAR EN BLANCO...', '#txt_placa');
        return false;
    }

    if ($('#txt_placa').val().length < 7) {
        mostraralertasconfoco('* EL CAMPO DEBE CONTENER 7 CARACTERES...', '#txt_placa');
        return false;
    }

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: 'viajes/'+$("#txt_placa").val()+'?buscar=placa',
        beforeSend:function()
        {            
            $('#bloque').block({ 
                message: '<h1><img src="img/img_cromotex/cargador_2.gif" />RECUPERANDO INFORMACION</h1>', 
                css: { border: '5px solid #a00',width: '350px' } 
            });
        },
        success: function(data) {
            if (data.length === 0) 
            {
                MensajeAdvertencia('NO SE ENCONTRO ESTA PLACA: ' + $("#txt_placa").val().toUpperCase());
            }
            else
            {
                crear_tabla_viajes_vehiculo(data[0].veh_id,data[0].veh_placa);  
            } 
            $('#bloque').unblock();                                                                
        },
        error: function(data) {
            MensajeAdvertencia("OCURRIO UN ERROR, COMUNICARSE CON EL ADMINISTRADOR");
            console.log('error');
            console.log(data);
        }
    });
}

function crear_tabla_viajes_vehiculo(veh_id,veh_placa)
{
    $.confirm({
        icon:'fa fa-tasks',
        title: 'DETALLE DE VIAJES DE PARA LA PLACA: ' + veh_placa,
        type: 'red',
        animationBounce: 2,
        typeAnimated: true,
        backgroundDismiss: false,
        backgroundDismissAnimation: 'glow',
        columnClass: 'col-md-6',
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
        content:'<div class="row">'+
                    '<div class="col-md-12 tblvehiculosviaje">'+
                        '<div class="form-group">'+
                            '<table id="tblvehiculosviaje"></table>'+
                            '<div id="paginador_tblvehiculosviaje"></div>'+                                                                  
                        '</div>'+
                    '</div>'+
                '</div>',
        onContentReady: function () 
        {
            jQuery("#tblvehiculosviaje").jqGrid({
                url: 'viajes/'+veh_id+'?grid=viajes_vehiculos',
                datatype: 'json', mtype: 'GET',
                height: '450px', autowidth: false,
                toolbarfilter: true,
                rownumbers: true,
                rownumWidth: 40,
                colNames: ['ID', 'RUTAS', 'FECHA', 'ESTADO', 'ESTILO'],
                rowNum: 31, sortname: 'pro_id', sortorder: 'asc', viewrecords: true, align: "center",
                colModel: [
                    {name: 'via_id', index: 'via_id', align: 'left',width: 10, hidden:true},
                    {name: 'pro_rutas', index: 'pro_rutas', align: 'center', width: 150, sortable: false, formatter: RutaEstilo},
                    {name: 'via_fecha', index: 'via_fecha', align: 'center', width: 150, formatter: 'date', formatoptions: {srcformat: 'Y-m-d', newformat: 'd/m/Y'}, sortable: false},
                    {name: 'via_estado', index: 'via_estado', align: 'center', width: 145, sortable: false, formatter: EstadoEstilo},
                    {name: 'pro_estilo', index: 'pro_estilo', align: 'center', width: 10, hidden:true}
                ],
                loadComplete: function(dat){
                    console.log($('#126').children()[2]);
                    if(dat.total != 0){
                        for(var i = 0; i < dat.rows.length; i++)
                        {
                            $($('#'+dat.rows[i].id).children()[2]).css("background-color", dat.rows[i].cell[4]);
                        }
                    }else{
                        $('.tblvehiculosviaje').block({ 
                            message: '<h1>NO SE ENCONTRO INFORMACION PARA ESTA PLACA</h1>', 
                            css: { border: '5px solid #a00', width: '370px' }
                        });
                    }
                },
                pager: '#paginador_tblvehiculosviaje',
                rowList: [31, 62, 93, 124],      
                cmTemplate: { sortable: false },
            });
        }
    }); 
}

jQuery(document).on("click", "#btn_limpiar_datos", function(){
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: 'viajes/0?generar=limpiar_datos',
        beforeSend:function()
        {            
            $('#bloque').block({ 
                message: '<h1><img src="img/img_cromotex/cargador_2.gif" />CARGANADO INFORMACION</h1>', 
                css: { border: '5px solid #a00',width: '350px' } 
            });
        },
        success: function(data) {
            jQuery("#tblviajes").jqGrid('setGridParam', {
                url: 'viajes/0?grid=viajes'
            }).trigger('reloadGrid');
            console.log(data);
            $('#bloque').unblock();                                                                
        },
        error: function(data) {
            MensajeAdvertencia("OCURRIO UN ERROR, COMUNICARSE CON EL ADMINISTRADOR");
            console.log('error');
            console.log(data);
        }
    });
});