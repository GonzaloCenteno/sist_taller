<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>ABASTECIMIENTO VALE N° - {{ $vale_cabecera->vca_numvale }}</title>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
        
        <style>
            .move-ahead { counter-increment: page 2; position: absolute; visibility: hidden; }
            .pagenum:after { content:' ' counter(page); }
            .footer {position: fixed }
            .bordes-pagina 
            {
                border-right:0px; border-bottom: 0px; border-left: 0px; border-top: 0px;
            }
        </style>
    </head>

    <body>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top:110px; margin-bottom: 50px;  font-size: 1.8em;">
            <thead>
                <tr>
                    <td colspan="4" style="text-align: left;" class="bordes-pagina">{{ $sucursal->suc_razsocial }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: left;" class="bordes-pagina">SALIDA POR CONSUMO DE COMBUSTIBLE N° - {{ $vale_cabecera->vca_numvale }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;width: 25%;" class="bordes-pagina">ALMACEN</td>
                    <td colspan="3" style="text-align: left;width: 75%;" class="bordes-pagina">:  {{ $almacen->alm_codigo }} - {{ $almacen->alm_descripcion }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;width: 25%;" class="bordes-pagina">CENTRO DE COSTOS</td>
                    <td colspan="3" style="text-align: left;width: 75%;" class="bordes-pagina">:  61.3.2.1.001</td>
                </tr>
                <tr>
                    <td style="text-align: left;width: 25%;" class="bordes-pagina">VEHICULO</td>
                    <td colspan="3" style="text-align: left;width: 75%;" class="bordes-pagina">:  {{ $vale_cabecera->vehiculo }} - UNIDAD :  {{ $vale_cabecera->veh_placa }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;width: 25%;" class="bordes-pagina">CHOFER</td>
                    <td colspan="3" style="text-align: left;width: 75%;" class="bordes-pagina">:  {{ $vale_cabecera->tripulante }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;width: 25%;" class="bordes-pagina">FECHA EMISION</td>
                    <td colspan="3" style="text-align: left;width: 75%;" class="bordes-pagina">:  {{ $vale_cabecera->vca_fecha }} - HORA :  {{ $vale_cabecera->vca_hora }} - RUTA :  {{ $vale_cabecera->est_descripcion }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;width: 25%;" class="bordes-pagina">VELOCIMETRO(KM)</td>
                    <td colspan="3" style="text-align: left;width: 75%;" class="bordes-pagina">:  {{ $vale_cabecera->vca_kilometraje }} - Nro BOMBA :  {{ $vale_cabecera->bom_id }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;width: 25%;" class="bordes-pagina">CONTROMETRO INICIAL</td>
                    <td colspan="3" style="text-align: left;width: 75%;" class="bordes-pagina">:  {{ $vale_cabecera->vca_cntmtri }} - CONTROMETRO FINAL :  {{ $vale_cabecera->vca_cntmtrf }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;width: 25%;border-right:0px;border-left:0px;border-top-style: dashed;border-bottom-style: dashed">COD. PRODUCTO</td>
                    <td style="text-align: center;width: 20%;border-right:0px;border-left:0px;border-top-style: dashed;border-bottom-style: dashed">PRODUCTO</td>
                    <td style="text-align: center;width: 20%;border-right:0px;border-left:0px;border-top-style: dashed;border-bottom-style: dashed">UNIDAD</td>
                    <td style="text-align: center;width: 20%;border-right:0px;border-left:0px;border-top-style: dashed;border-bottom-style: dashed">CANTIDAD</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina">{{ $vale_detalle->codigo }}</td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina">{{ $vale_detalle->descripcion }}</td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina">{{ $vale_detalle->unidad }}</td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina">{{ $vale_detalle->cantidad }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                </tr>
                <tr>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                </tr>
                <tr>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                </tr>
                <tr>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                </tr>
                <tr>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                </tr>
                <tr>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                    <td style="text-align: center;width: 25%;" class="bordes-pagina"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align: center;width: 25%;border-right:0px;border-left:0px;border-bottom:0px;border-top-style: dashed">SOLICITADO POR</td>
                    <td style="text-align: center;width: 25%;border-right:0px;border-left:0px;border-bottom:0px;border-top-style: dashed">V<sup>o</sup>B<sup>o</sup></td>
                    <td style="text-align: center;width: 25%;border-right:0px;border-left:0px;border-bottom:0px;border-top-style: dashed">DESPACHADO POR</td>
                    <td style="text-align: center;width: 25%;border-right:0px;border-left:0px;border-bottom:0px;border-top-style: dashed">RECIBIDO POR</td>
                </tr>
            </tfoot>
        </table>
        <h2>FECHA DE IMPRESION {{ \Carbon\Carbon::parse(date('Y-m-d'))->format('d/m/Y') }} {{ date('H:i:s') }}</h2>
    </body>

</html>