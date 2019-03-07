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
        <h4 class="m-0">REGISTRO DE RUTAS</h4>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="col-lg-12">
            <center>
                <button id="btn_nueva_ruta" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus-square"></i> CREAR RUTA</button>
                <button id="btn_modificar_ruta" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR RUTA</button></center>
        </div>
        <br>
        <div class="form-group col-md-12">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblrutas"></table>
                <div id="paginador_tblrutas"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

<div class="modal fade" id="ModalRutas">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>DESCRIPCION:</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                        </div>
                        <input type="text" id="txt_rut_descripcion" class="form-control text-center text-uppercase" placeholder="ESCRIBIR DESCRIPCION DE LA RUTA" maxlength="3">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_crear_ruta" class="btn btn-primary btn-xl"></button>
                <button type="button" id="btn_actualizar_ruta" class="btn btn-warning btn-xl"></button>
                <button type="button" id="btn_cerrar_modal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/rutas.js') }}"></script>
@stop

@endsection


