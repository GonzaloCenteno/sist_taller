jQuery(document).on("click", "#btn_consumo_mensual", function(){
    window.open('reportes_grifo/0?reportes=consumo_mensual');
});

jQuery(document).on("click", "#btn_consumo_xdia", function(){
    Reporte2 = $('#ModalReporte2').modal({backdrop: 'static', keyboard: false});
    Reporte2.find('.modal-title').text('BUSCAR POR RANGO DE FECHAS');
    Reporte2.find('#btn_imprimir_reporte2').html('<i class="fa fa-print"></i> IMPRIMIR').show();
    
    $('#txt_fecha_inicio').val('');
    $('#txt_fecha_fin').val('');
});

jQuery(document).on("click", "#btn_imprimir_reporte2", function(){
    if ($('#txt_fecha_inicio').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA INICIO ES OBLIGATORIO...', '#txt_fecha_inicio');
        return false;
    }
    if ($('#txt_fecha_fin').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA FIN ES OBLIGATORIO...', '#txt_fecha_fin');
        return false;
    }
    window.open('reportes_grifo/0?reportes=consumo_xdia&fecha_inicio='+$("#txt_fecha_inicio").val()+'&fecha_fin='+$("#txt_fecha_fin").val());
});