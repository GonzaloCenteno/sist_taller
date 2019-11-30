
jQuery(document).on("click", "#menu_push", function(){    
    if ($("#body_push").hasClass('sidebar-mini sidebar-collapse')) 
    {
        setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblvalesgrifo').setGridWidth(width);
        }, 300);
    }
    else
    {
       setTimeout(function (){
            var width = $('#contenedor').width();
            $('#tblvalesgrifo').setGridWidth(width);
       }, 300);
    } 
});

jQuery(document).on("click", "#btn_anular_vale_grifo", function(){
    var vca_id = $(this).val();
    swal({
        title: '¿ESTA SEGURO ANULAR ESTE VALE?',
        html: '<b>VALE N°: '+$(this).data("vale")+'</b>',
        type: 'warning',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ACEPTAR',
        cancelButtonText: 'CANCELAR',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        showCancelButton: true,
        buttonsStyling: false,
        allowOutsideClick: false,
        allowEscapeKey:false,
        allowEnterKey:false,
        reverseButtons: true
        }).then(function(result) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: 'vales_grifo/destroy',
                type: 'POST',
                data:
                {
                    _method: 'delete',
                    _token:  $('meta[name="csrf-token"]').attr('content'),
                    vca_id:  vca_id
                },
                beforeSend:function()
                {            
                    $('#tabla_vales').block({ 
                        message: '<h1><img src="img/img_cromotex/cargador_2.gif" />MODIFICANDO INFORMACION</h1>', 
                        css: { border: '5px solid #a00',width: '350px' } 
                    });
                },
                success: function(data) 
                {
                    if (data == 1) 
                    {
                        $('#tabla_vales').unblock();
                        MensajeConfirmacion('EL VALE FUE ANULADO');
                        jQuery("#tblvalesgrifo").jqGrid('setGridParam', {
                            url: 'vales_grifo/0?grid=vales&indice=0'
                        }).trigger('reloadGrid');
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
});

jQuery(document).on("click", "#btn_act_tblvalesgrifo", function(){
    $("#txt_buscar_vale").val('');
    $("#txt_buscar_tripulante").val('');
    $("#txt_buscar_placa").val('');
    $("#txt_fec_inicio").val('');
    $("#txt_fec_fin").val('');
    
    jQuery("#tblvalesgrifo").jqGrid('setGridParam', {
        url: 'vales_grifo/0?grid=vales&indice=0'
    }).trigger('reloadGrid');
});

jQuery(document).on("click", "#btn_bus_vales_grifo", function(){
    jQuery("#tblvalesgrifo").jqGrid('setGridParam', {
        url: 'vales_grifo/0?grid=vales&indice=1&vale='+$("#txt_buscar_vale").val()+'&tripulante='+$("#txt_buscar_tripulante").val()+'&placa='+$("#txt_buscar_placa").val()+'&fecinicio='+$("#txt_fec_inicio").val()+'&fecfin='+$("#txt_fec_fin").val()
    }).trigger('reloadGrid');
});