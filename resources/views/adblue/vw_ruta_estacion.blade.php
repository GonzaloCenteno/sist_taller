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
        <h4 class="m-0">REGISTRO DE RUTA - ESTACIONES</h4>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="col-lg-12">
            <center>
                <div id="listadoButtons">
                    <button id="btn_vw_rtestacion_Nuevo" type="button" onclick="mostrarformulario(true)" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-plus"></i> NUEVO REGISTRO</button>
                    <button id="btn_vw_rtestacion_Editar" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-pencil"></i> EDITAR</button>
                </div>

                <div id="formularioButtons" style="display:none;">
                    <!--<button id="btn_vw_equipos_Guardar" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-save"></i> GUARDAR REGISTRO</button>-->
                    <button id="btn_vw_equipos_Cancelar" type="button" class="btn btn-xl btn-default" readonly="readonly"><i class="fa fa-arrow-circle-left"></i> REGRESAR</button>
                </div> 

                <div id="formularioButtonsEditar" style="display:none;">
                    <button id="btn_vw_rtestacion_Modificar" type="button" class="btn btn-xl btn-warning" readonly="readonly"><i class="fa fa-pencil"></i> MODIFICAR REGISTRO</button>
                    <button id="btn_vw_equipos_Cancelar" type="button" class="btn btn-xl btn-default" readonly="readonly"><i class="fa fa-arrow-circle-left"></i> REGRESAR</button>
                </div> 
            </center>
        </div>
        <br>
        
        <div class="col-xs-12 center-block" id="formularioRegistros" style="display: none;">
            <form id="FormularioRtesaciones" name="FormularioRtesaciones" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label>SELECCIONAR UNA RUTA:</label>

                            <select class="form-control select2" style="width: 100%;" id="cbx_rutas" name="cbx_rutas">
                                @foreach($rutas as $ruta)
                                <option value="{{ $ruta->rut_id }}"> {{ $ruta->rut_descripcion }} </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-md-3" style="padding-top: 25px;">
                        <div class="form-group">
                            <button id="btn_generar_estaciones" type="button" class="btn btn-xl btn-danger btn-block" readonly="readonly"><i class="fa fa-plus-square"></i></button>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                            <thead style="background-color:#A9D0F5">
                            <th style="width: 10%;">BORRAR</th>
                            <th style="width: 70%;">ESTACION</th>
                            <th style="width: 20%;">CONSUMO</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <div style="padding-left: 10px">
                <button id="btn_vw_rtestacion_Guardar" type="button" class="btn btn-xl btn-danger" readonly="readonly"><i class="fa fa-save"></i> GUARDAR REGISTROS</button>
            </div>
        </div>
        
        <br>
        <div class="form-group col-md-12" id="listadoRegistros">
            <div class="form-group col-md-12" id="contenedor">
                <table id="tblrutaestacion"></table>
                <div id="paginador_tblrutaestacion"></div>                         
            </div>
        </div>
    </div>
</div><!-- /.card -->

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/adblue/ruta_estacion.js') }}"></script>
@stop

@endsection


