@extends('principal.p_inicio')

@section('content')
<style>
/*.modal-lg { 
    max-width: 75% !important;
    padding-left: 50px;
}    */
</style>
<br>
<div class="card card-danger card-outline">
    <div class="card-header">
        <h5 class="m-0">Featured</h5>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="card-body">
        <h5 class="card-title">Card title</h5>

        <p class="card-text">
            Some quick example text to build on the card title and make up the bulk of the card's
            content.
        </p>
        <a href="#" class="card-link">Card link</a>
        <a href="#" class="card-link">Another link</a>
        <div class="col-xs-12 center-block">
            <table id="tblMedicamentos" class="table table-bordered dt-responsive compact">
                <thead>
                    <tr style="color: white;background-color: #DB3543;">
                        <th style="width: 7%;">#</th>
                        <th style="width: 20%;">NOMBRE</th>
                        <th style="width: 83%;">DN</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div> 
        <div class="col-lg-12">
            <button type="button" class="btn btn-sm btn-danger" readonly="readonly" id="btn_modal">ABRIR MODAL</button>
            <button type="button" class="btn btn-sm btn-warning" id="bnt_prueba" >ABRIR MODAL</button>
        </div>
    </div>
</div><!-- /.card -->

<div class="modal fade" id="modalMedicamentos">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Default Modal</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>DESCRIPCION:</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-file-text"></i>
                        </div>
                        <input type="text" id="mdl_descripcion" class="form-control text-uppercase input-lg" placeholder="DESCRIPCION DEL MEDICAMENTO" autofocus="">
                    </div>
                    <!-- /.input group -->
                </div>

                <div class="form-group">
                    <label>UNIDAD:</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-arrows"></i>
                        </div>
                        <input type="text" id="mdl_unidad" class="form-control text-uppercase input-lg" placeholder="ESCRIBIR TIPO DE UNIDAD">
                    </div>
                    <!-- /.input group -->
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="cerrar_modal" class="btn btn-danger btn-lg" data-dismiss="modal">CERRAR</button>
                <button type="button" id="crear_medicamento" class="btn btn-primary btn-lg"></button>
                <button type="button" id="modificar_medicamento" class="btn btn-warning btn-lg"></button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script>
    
    jQuery(document).ready(function($){
        
        $("#tblMedicamentos").DataTable({
            "lengthMenu": [[5, 10, 15, -1], [5, 10, 15, "Todo"]],
            "info": true,
            "ordering": true,
            "destroy":true,
            "searching": true,
            "processing" : true,
            "serverSide" : true,
            "responsive": true,
            "paging": true,
            "autoWidth": false,
            "ajax": "dashboard/create",
            "columns":[
                    {data: 'id_usuario'},
                    {data: 'nombre_usuario'},
                    {data: 'dn'},
                    ],
            "order": [[ 0, "desc" ]],
            "language" : idioma_espanol,
            select: {
                style: 'single'
            },
        });
                
        $('.dataTables_filter input[type="search"]').css(
            {'width':'305px','display':'inline-block'}
        );
        
        $('.dataTables_filter input[type="search"]').attr("placeholder","FILTRO DE BUSQUEDA POR TODOS LOS CAMPOS");
        
        $('select[name="tblMedicamentos_length"]').css(
            {'width':'240px','display':'inline-block'}
        );
    })
        

    var idioma_espanol = {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Registros del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
    
    
    jQuery(document).on("click", "#bnt_prueba", function () {

        MensajeConfirmacion('EL RESPUESTA FUE ENVIADA CON EXITO');
    })

    jQuery(document).on("click", "#crear_medicamento", function () {

        mostraralertasconfoco('EL RESPUESTA FUE ENVIADA CON EXITO');
    })

    jQuery(document).on("click", "#btn_modal", function () {
        
        Medicamentos = $('#modalMedicamentos').modal({backdrop: 'static', keyboard: false})
        Medicamentos.find('.modal-title').text('CREAR NUEVO MEDICAMENTOS');
        Medicamentos.find('#crear_medicamento').text('AGREGAR NUEVO').show();
        Medicamentos.find('#modificar_medicamento').hide();
    })
    
</script>
@stop
@endsection


