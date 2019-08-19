@extends('principal.p_inicio')
@section('title', 'ALM. COMBUSTIBLE') 
@section('content')
<style>
    /*.modal-lg { 
        max-width: 75% !important;
        padding-left: 50px;
    }    */
</style>
<br>
<div class="card card-danger card-outline" id="card_principal">
    <div class="card-header align-self-center">
        <h4 class="m-0"><i class="fa fa-bar-chart fa-2x" aria-hidden="true"></i> LOGISTICA - ALMACEN GENERAL DE COMBUSTIBLE</h4>
    </div>
    <div class="card-body">
        <form id="FormularioCombustible" name="FormularioCombustible" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <input type="hidden" id="vca_id" name="vca_id" value="0">
                <input type="hidden" id="trh_ntransaccion" name="trh_ntransaccion" value="0">
                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label>NUMERO:</label>
                        <div class="input-group">
                            <input type="text" id="txt_vca_numvale" name="txt_vca_numvale" class="form-control text-center text-uppercase" maxlength="7" onClick="this.select()" style="font-weight: bold; font-size: 16pt;" onkeypress="return soloNumeroTab(event);">
                            <div class="input-group-prepend">
                                <button type="button" onclick="buscar_nrovale();" class="btn btn-warning"><i class="fa fa-credit-card"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label>FECHA EMISION:</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" id="txt_vca_fecemision" name="txt_vca_fecemision" readonly="readonly" class="form-control text-center FechaControl" style="font-weight: bold;">
                        </div>

                    </div>
                </div>

                <div class="col-md-6 col-xs-3">
                    <div class="form-group">
                        <label>REFERENCIA:</label>

                        <input type="text" id="txt_referencia" name="txt_referencia" class="form-control text-center text-uppercase" onClick="this.select()" style="font-weight: bold;">

                    </div>
                </div>

                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label>ALMACEN:</label>

                        <input type="text" id="txt_alm_codigo" name="txt_alm_codigo" class="form-control text-center text-uppercase" value="{{ $almacen->alm_codigo }}" disabled="disabled" style="font-weight: bold;">

                    </div>
                </div>

                <div class="col-md-6 col-xs-3">
                    <div class="form-group">
                        <label>DESCRIPCION ALMACEN:</label>

                        <input type="text" id="txt_alm_descripcion" name="txt_alm_descripcion" class="form-control text-center text-uppercase" value="{{ $almacen->alm_descripcion }}" disabled="disabled" style="font-weight: bold;">

                    </div>
                </div>

                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label>ESTADO:</label>
                        
                        <input type="text" id="txt_estado" class="form-control text-center text-uppercase" disabled="disabled" style="font-weight: bold;" style="font-weight: bold;">

                    </div>
                </div>

                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label>CODIGO TRABAJADOR:</label>
                        <div class="input-group">
                            <input type="hidden" id="hiddentxt_tri_nrodoc" name="hiddentxt_tri_nrodoc">
                            <input type="text" id="txt_tri_nrodoc" name="txt_tri_nrodoc" class="form-control text-center text-uppercase" onClick="this.select()" style="font-weight: bold;">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-danger"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9 col-xs-3">
                    <div class="form-group">
                        <label>NOMBRE TRABAJADOR:</label>
                        <div class="input-group">
                            <input type="text" id="txt_tri_nombres" name="txt_tri_nombres" class="form-control text-center text-uppercase" onClick="this.select()" style="font-weight: bold;">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-danger"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label>PLACA:</label>
                        <div class="input-group">
                            <input type="hidden" id="hiddentxt_veh_placa" name="hiddentxt_veh_placa">
                            <input type="text" id="txt_veh_placa" name="txt_veh_placa" class="form-control text-center text-uppercase" onClick="this.select()" style="font-weight: bold;">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-danger"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xs-3">
                    <div class="form-group">
                        <label>DESCRIPCION:</label>

                        <input type="text" id="txt_veh_descripcion" name="txt_veh_descripcion" class="form-control text-center text-uppercase" onClick="this.select()" style="font-weight: bold;">

                    </div>
                </div>

                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label>CENTRO DE COSTO:</label>

                        <input type="text" id="txt_centro_costo" class="form-control text-center text-uppercase" value="{{ $ctr_costo->cec_codigo }}" disabled="disabled" style="font-weight: bold;">

                    </div>
                </div>

                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label>CONTOMETRO INICIAL:</label>

                        <input type="text" id="txt_cntmtri" name="txt_cntmtri" class="form-control text-center text-uppercase" readonly="true" style="font-weight: bold;">

                    </div>
                </div>

                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label>CONTOMETRO FINAL:</label>

                        <input type="text" id="txt_cntmtrf" name="txt_cntmtrf" class="form-control text-center text-uppercase" readonly="true" style="font-weight: bold;">

                    </div>
                </div>

                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label>KILOMETRAJE ANTERIOR:</label>

                        <input type="text" id="txt_kilometraje_ant" name="txt_kilometraje_ant" class="form-control text-center text-uppercase" disabled="disabled" style="font-weight: bold;">

                    </div>
                </div>

                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label>KILOMETRAJE:</label>

                        <input type="text" id="txt_kilometraje" name="txt_kilometraje" class="form-control text-center text-uppercase" onClick="this.select()" style="font-weight: bold;" onkeypress="return soloNumeroTab(event);">

                    </div>
                </div>

                <div class="col-md-3 col-xs-3 text-center align-self-center">
                    <div class="form-group">

                        <button id="btn_conectar_grifo" type="button" class="btn btn-xl btn-outline-danger btn-block" readonly="readonly"><i class="fa fa-sign-out fa-4x"></i> CONECTAR GRIFO</button>

                    </div>
                </div>

                <div class="col-md-4 col-xs-3">
                    <div class="form-group">
                        <label>BOMBA:</label>

                        <select class="form-control" style="width: 100%;" id="cbx_bomba" name="cbx_bomba">
                            <option value="0">..:: SELECCIONAR BOMBA ::.. </option>
                            @foreach($bombas as $bomba)
                                <option value="{{ $bomba->bom_id }}">..:: {{ $bomba->bom_descripcion }} ::.. </option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <div class="col-md-5 col-xs-3">
                    <div class="form-group">
                        <label>RUTA/AREA:</label>

                        <select class="form-control" style="width: 100%;" id="cbx_ruta_area" name="cbx_ruta_area">
                            <option value="0">..:: SELECCIONAR RUTA ::.. </option>
                            @foreach($estaciones as $estacion)
                                <option value="{{ $estacion->est_id }}">..:: {{ $estacion->est_descripcion }} ::..</option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <div class="form-group col-md-12">
                    <div class="form-group col-md-12" id="contenedor">
                        <table id="tblvaledetalle"></table>
                        <div id="paginador_tblvaledetalle"></div>                         
                    </div>
                </div>

            </div>
        </form>
    </div>

    <div class="card-footer">
        <div class="row text-center">
            <div class="col-md-2 col-xs-3">
                <button id="btn_nueva_capacidad" type="button" class="btn btn-xl btn-outline-dark btn-block" readonly="readonly"><i class="fa fa-plus-square"></i> NUEVO</button>
            </div>
            <div class="col-md-2 col-xs-3">
                <button id="btn_nueva_capacidad" type="button" class="btn btn-xl btn-outline-primary btn-block" readonly="readonly"><i class="fa fa-folder-open-o"></i> ABRIR</button>
            </div>
            <div class="col-md-2 col-xs-3">
                @if( $permiso[0]->btn_new == 1 )
                    <button id="btn_guardar" type="button" class="btn btn-xl btn-outline-danger btn-block" readonly="readonly"><i class="fa fa-save"></i> GUARDAR</button>
                @else
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-outline-danger btn-block" readonly="readonly"><i class="fa fa-save"></i> GUARDAR</button>
                @endif
            </div>
            <div class="col-md-2 col-xs-3">
                <button id="btn_deshacer" type="button" class="btn btn-xl btn-outline-info btn-block" readonly="readonly"><i class="fa fa-trash-o"></i> DESHACER</button>
            </div>
            <div class="col-md-2 col-xs-3">
                @if( $permiso[0]->btn_edit == 1 )
                    <button id="btn_editar" type="button" class="btn btn-xl btn-outline-secondary btn-block" readonly="readonly"><i class="fa fa-pencil-square-o"></i> EDITAR</button>
                @else
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-outline-secondary btn-block" readonly="readonly"><i class="fa fa-pencil-square-o"></i> EDITAR</button>
                @endif
            </div>
            <div class="col-md-2 col-xs-3">
                @if( $permiso[0]->btn_edit == 1 )
                    <button id="btn_imprimir" type="button" class="btn btn-xl btn-outline-success btn-block" readonly="readonly"><i class="fa fa-print"></i> IMPRIMIR</button>
                @else
                    <button onclick="sin_permiso();" type="button" class="btn btn-xl btn-outline-success btn-block" readonly="readonly"><i class="fa fa-print"></i> IMPRIMIR</button>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalValeImpresion">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span></button>
            </div>

            <div class="modal-body">
                <div class="modal-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="iframe_vale" class="embed-responsive-item" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_cerrar_modal" class="btn btn-danger btn-xl" data-dismiss="modal"><i class="fa fa-times-rectangle-o"></i> CERRAR</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/grifo/almacen_general.js') }}"></script>

<script type="text/javascript">
qz.security.setCertificatePromise(function(resolve, reject) {

    resolve("-----BEGIN CERTIFICATE-----\n" +
            "MIIFAzCCAuugAwIBAgICEAIwDQYJKoZIhvcNAQEFBQAwgZgxCzAJBgNVBAYTAlVT\n" +
            "MQswCQYDVQQIDAJOWTEbMBkGA1UECgwSUVogSW5kdXN0cmllcywgTExDMRswGQYD\n" +
            "VQQLDBJRWiBJbmR1c3RyaWVzLCBMTEMxGTAXBgNVBAMMEHF6aW5kdXN0cmllcy5j\n" +
            "b20xJzAlBgkqhkiG9w0BCQEWGHN1cHBvcnRAcXppbmR1c3RyaWVzLmNvbTAeFw0x\n" +
            "NTAzMTkwMjM4NDVaFw0yNTAzMTkwMjM4NDVaMHMxCzAJBgNVBAYTAkFBMRMwEQYD\n" +
            "VQQIDApTb21lIFN0YXRlMQ0wCwYDVQQKDAREZW1vMQ0wCwYDVQQLDAREZW1vMRIw\n" +
            "EAYDVQQDDAlsb2NhbGhvc3QxHTAbBgkqhkiG9w0BCQEWDnJvb3RAbG9jYWxob3N0\n" +
            "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtFzbBDRTDHHmlSVQLqjY\n" +
            "aoGax7ql3XgRGdhZlNEJPZDs5482ty34J4sI2ZK2yC8YkZ/x+WCSveUgDQIVJ8oK\n" +
            "D4jtAPxqHnfSr9RAbvB1GQoiYLxhfxEp/+zfB9dBKDTRZR2nJm/mMsavY2DnSzLp\n" +
            "t7PJOjt3BdtISRtGMRsWmRHRfy882msBxsYug22odnT1OdaJQ54bWJT5iJnceBV2\n" +
            "1oOqWSg5hU1MupZRxxHbzI61EpTLlxXJQ7YNSwwiDzjaxGrufxc4eZnzGQ1A8h1u\n" +
            "jTaG84S1MWvG7BfcPLW+sya+PkrQWMOCIgXrQnAsUgqQrgxQ8Ocq3G4X9UvBy5VR\n" +
            "CwIDAQABo3sweTAJBgNVHRMEAjAAMCwGCWCGSAGG+EIBDQQfFh1PcGVuU1NMIEdl\n" +
            "bmVyYXRlZCBDZXJ0aWZpY2F0ZTAdBgNVHQ4EFgQUpG420UhvfwAFMr+8vf3pJunQ\n" +
            "gH4wHwYDVR0jBBgwFoAUkKZQt4TUuepf8gWEE3hF6Kl1VFwwDQYJKoZIhvcNAQEF\n" +
            "BQADggIBAFXr6G1g7yYVHg6uGfh1nK2jhpKBAOA+OtZQLNHYlBgoAuRRNWdE9/v4\n" +
            "J/3Jeid2DAyihm2j92qsQJXkyxBgdTLG+ncILlRElXvG7IrOh3tq/TttdzLcMjaR\n" +
            "8w/AkVDLNL0z35shNXih2F9JlbNRGqbVhC7qZl+V1BITfx6mGc4ayke7C9Hm57X0\n" +
            "ak/NerAC/QXNs/bF17b+zsUt2ja5NVS8dDSC4JAkM1dD64Y26leYbPybB+FgOxFu\n" +
            "wou9gFxzwbdGLCGboi0lNLjEysHJBi90KjPUETbzMmoilHNJXw7egIo8yS5eq8RH\n" +
            "i2lS0GsQjYFMvplNVMATDXUPm9MKpCbZ7IlJ5eekhWqvErddcHbzCuUBkDZ7wX/j\n" +
            "unk/3DyXdTsSGuZk3/fLEsc4/YTujpAjVXiA1LCooQJ7SmNOpUa66TPz9O7Ufkng\n" +
            "+CoTSACmnlHdP7U9WLr5TYnmL9eoHwtb0hwENe1oFC5zClJoSX/7DRexSJfB7YBf\n" +
            "vn6JA2xy4C6PqximyCPisErNp85GUcZfo33Np1aywFv9H+a83rSUcV6kpE/jAZio\n" +
            "5qLpgIOisArj1HTM6goDWzKhLiR/AeG3IJvgbpr9Gr7uZmfFyQzUjvkJ9cybZRd+\n" +
            "G8azmpBBotmKsbtbAU/I/LVk8saeXznshOVVpDRYtVnjZeAneso7\n" +
            "-----END CERTIFICATE-----\n" +
            "--START INTERMEDIATE CERT--\n" +
            "-----BEGIN CERTIFICATE-----\n" +
            "MIIFEjCCA/qgAwIBAgICEAAwDQYJKoZIhvcNAQELBQAwgawxCzAJBgNVBAYTAlVT\n" +
            "MQswCQYDVQQIDAJOWTESMBAGA1UEBwwJQ2FuYXN0b3RhMRswGQYDVQQKDBJRWiBJ\n" +
            "bmR1c3RyaWVzLCBMTEMxGzAZBgNVBAsMElFaIEluZHVzdHJpZXMsIExMQzEZMBcG\n" +
            "A1UEAwwQcXppbmR1c3RyaWVzLmNvbTEnMCUGCSqGSIb3DQEJARYYc3VwcG9ydEBx\n" +
            "emluZHVzdHJpZXMuY29tMB4XDTE1MDMwMjAwNTAxOFoXDTM1MDMwMjAwNTAxOFow\n" +
            "gZgxCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOWTEbMBkGA1UECgwSUVogSW5kdXN0\n" +
            "cmllcywgTExDMRswGQYDVQQLDBJRWiBJbmR1c3RyaWVzLCBMTEMxGTAXBgNVBAMM\n" +
            "EHF6aW5kdXN0cmllcy5jb20xJzAlBgkqhkiG9w0BCQEWGHN1cHBvcnRAcXppbmR1\n" +
            "c3RyaWVzLmNvbTCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBANTDgNLU\n" +
            "iohl/rQoZ2bTMHVEk1mA020LYhgfWjO0+GsLlbg5SvWVFWkv4ZgffuVRXLHrwz1H\n" +
            "YpMyo+Zh8ksJF9ssJWCwQGO5ciM6dmoryyB0VZHGY1blewdMuxieXP7Kr6XD3GRM\n" +
            "GAhEwTxjUzI3ksuRunX4IcnRXKYkg5pjs4nLEhXtIZWDLiXPUsyUAEq1U1qdL1AH\n" +
            "EtdK/L3zLATnhPB6ZiM+HzNG4aAPynSA38fpeeZ4R0tINMpFThwNgGUsxYKsP9kh\n" +
            "0gxGl8YHL6ZzC7BC8FXIB/0Wteng0+XLAVto56Pyxt7BdxtNVuVNNXgkCi9tMqVX\n" +
            "xOk3oIvODDt0UoQUZ/umUuoMuOLekYUpZVk4utCqXXlB4mVfS5/zWB6nVxFX8Io1\n" +
            "9FOiDLTwZVtBmzmeikzb6o1QLp9F2TAvlf8+DIGDOo0DpPQUtOUyLPCh5hBaDGFE\n" +
            "ZhE56qPCBiQIc4T2klWX/80C5NZnd/tJNxjyUyk7bjdDzhzT10CGRAsqxAnsjvMD\n" +
            "2KcMf3oXN4PNgyfpbfq2ipxJ1u777Gpbzyf0xoKwH9FYigmqfRH2N2pEdiYawKrX\n" +
            "6pyXzGM4cvQ5X1Yxf2x/+xdTLdVaLnZgwrdqwFYmDejGAldXlYDl3jbBHVM1v+uY\n" +
            "5ItGTjk+3vLrxmvGy5XFVG+8fF/xaVfo5TW5AgMBAAGjUDBOMB0GA1UdDgQWBBSQ\n" +
            "plC3hNS56l/yBYQTeEXoqXVUXDAfBgNVHSMEGDAWgBQDRcZNwPqOqQvagw9BpW0S\n" +
            "BkOpXjAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBCwUAA4IBAQAJIO8SiNr9jpLQ\n" +
            "eUsFUmbueoxyI5L+P5eV92ceVOJ2tAlBA13vzF1NWlpSlrMmQcVUE/K4D01qtr0k\n" +
            "gDs6LUHvj2XXLpyEogitbBgipkQpwCTJVfC9bWYBwEotC7Y8mVjjEV7uXAT71GKT\n" +
            "x8XlB9maf+BTZGgyoulA5pTYJ++7s/xX9gzSWCa+eXGcjguBtYYXaAjjAqFGRAvu\n" +
            "pz1yrDWcA6H94HeErJKUXBakS0Jm/V33JDuVXY+aZ8EQi2kV82aZbNdXll/R6iGw\n" +
            "2ur4rDErnHsiphBgZB71C5FD4cdfSONTsYxmPmyUb5T+KLUouxZ9B0Wh28ucc1Lp\n" +
            "rbO7BnjW\n" +
            "-----END CERTIFICATE-----\n");
});

qz.security.setSignaturePromise(function(toSign) {
    return function(resolve, reject) {
        resolve();
    };
});


function startConnection(config) {
    if (!qz.websocket.isActive()) {


        qz.websocket.connect(config).then(function() {

            findVersion();
        }).catch(handleConnectionError);
    } else {
        alert("An active connection with QZ already exists.");

    }
}
var qzVersion = 0;
function findVersion() {
    qz.api.getVersion().then(function(data) {
        $("#qz-version").html(data);
        qzVersion = data;
    }).catch(displayError);
}
function displayError(err) {
    console.error(err);

}
function handleConnectionError(err) {
    updateState('Error', 'danger');

    if (err.target !== undefined) {
        if (err.target.readyState >= 2) { //if CLOSING or CLOSED
            displayError("Connection to QZ Tray was closed");
        } else {
            displayError("A connection error occurred, check log for details");
            console.error(err);
        }
    } else {
        displayError(err);
    }
}

function updateConfig() {
    var pxlSize = null;

    var pxlMargins = $("#pxlMargins").val();


    var copies = 1;
    var jobName = null;

    cfg.reconfigure({
        altPrinting: false, //$("#rawAltPrinting").prop('checked'),
        encoding: "", //$("#rawEncoding").val(),
        endOfDoc: "", //$("#rawEndOfDoc").val(),
        perSpool: "1", //$("#rawPerSpool").val(),
        colorType: "color", //"$("#pxlColorType").val(),
        copies: copies,
        density: "", //$("#pxlDensity").val(),
        duplex: false, //$("#pxlDuplex").prop('checked'),
        interpolation: "", //$("#pxlInterpolation").val(),
        jobName: jobName,
        margins: pxlMargins,
        orientation: "", //$("#pxlOrientation").val(),
        paperThickness: "", //$("#pxlPaperThickness").val(),
        printerTray: "", //$("#pxlPrinterTray").val(),
        rasterize: true, // $("#pxlRasterize").prop('checked'),
        rotation: "0", //$("#pxlRotation").val(),
        scaleContent: true, //$("#pxlScale").prop('checked'),
        size: pxlSize,
        units: "in"//$("input[name='pxlUnits']:checked").val()
    });
}

</script>

<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('menu-open');
    $('.{{ $permiso[0]->men_sistema }}').addClass('active');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('active');
    
    
    $("#txt_vca_fecemision").datepicker({ dateFormat: "dd/mm/yy", minDate: -5}).datepicker("setDate", new Date());
    $.datepicker.regional['es'] = 
    {
        closeText: 'Cerrar',
        prevText: '<<',
        nextText: '>>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
        $.datepicker.setDefaults($.datepicker.regional['es']);

    jQuery(document).ready(function($){
        startConnection();
        $("#txt_vca_numvale").focus();
        
        jQuery("#tblvaledetalle").jqGrid({
            url: '',
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            cmTemplate: { sortable: false },
            colNames: ['CODIGO', 'DESCRIPCION','UNIDAD','CANTIDAD','TANQUE P.','TANQUE S.','TANQUE A.'],
            rowNum: 100, sortname: 'trh_ntransaccion', sortorder: 'asc', viewrecords: false, align: "center",
            colModel: [
                {name: 'codigo', index: 'codigo', align: 'center',width: 50},
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 80},
                {name: 'unidad', index: 'unidad', align: 'center', width: 20},
                {name: 'cantidad', index: 'cantidad', align: 'center', width: 40},
                {name: 'tanquep', index: 'tanquep', align: 'center', width: 40},
                {name: 'tanquea', index: 'tanquea', align: 'center', width: 40},
                {name: 'tanques', index: 'tanques', align: 'center', width: 40}
            ],
            rownumbers: true,
            rownumWidth: 25, 
            pager: '#paginador_tblvaledetalle',
            rowList: [],       
            pgbuttons: false,     
            pgtext: null,       
        });

    });
</script>
@stop

@endsection


