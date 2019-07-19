<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>CONTROL DIARIO</title>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
        <style>
            .move-ahead { counter-increment: page 2; position: absolute; visibility: hidden; }
            .pagenum:after { content:' ' counter(page); }
            .footer {position: fixed }

        </style>
    </head>

    <body>
        <div class="datehead" style="font-size:0.8em;">
            {{ date('d-m-Y') }}
        </div>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px;">
            <tr>
                <td style="width: 10%; border: 0px;" >
                    TRANSPORTES CROMOTEX
                </td>
                <td style="width: 80%; padding-top: 0px; border:0px;">
                    <div id="details" class="sub2">
                        <div id="invoice" style="font-size:1em" >
                            <h1>CONTROL DIARIO DE ADBLUE - AREQUIPA LITROS</h1>
                        </div>
                        <div  style="width: 95%; border-top:1px solid #999; margin-top: 5px; margin-left: 25px"></div>
                    </div>
                </td>
                <td style="width: 10%;border: 0px;"></td>
            </tr>
        </table>

        <div class="subasunto" style=" margin-bottom:5px; text-align: left; padding-left: 30px;font-size:0.7em;"> 
            <br>
        </div>

        <div class="lado3" style="height: 435px; margin-bottom: 20px;">
            @foreach($meses as $mes)
                <h2>{{ strtoupper($mes->xmes_descripcion) }}</h2> 
                <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.0em;">
                    <thead style="font-size: 1em;">
                        <tr>
                            <th style="width: 12%;">FECHA INGRESO</th>
                            <th style="width: 12%;"></th>
                            <th style="width: 12%;"></th>
                            <th style="width: 12%;"></th>
                            <th style="width: 15%;">INGRESO ISOTANQUE AL AREA</th>
                            <th style="width: 15%;"></th>
                            <th style="width: 15%;">TOTAL SALIDA POR ISOTANQUE</th>
                            <th style="width: 9%;">STOP</th>
                            <th style="width: 15%;">EXCEDENTE DE ISOTANQUE</th>
                            <th style="width: 15%;">CANTIDAD EN LITROS</th>
                            <th style="width: 22%;">OBSERVACION</th>
                            <th rowspan="2" style="width: 15%;">TOTAL DE DESCARGA MENSUAL</th>
                            <th rowspan="2" style="width: 15%;">TOTAL ABASTECIMIENTO MENSUAL</th>
                            <th rowspan="2" style="width: 15%;">SALDO PROXIMO MES</th>   
                        </tr>
                    </thead>
                    <thead style="font-size: 1em;">
                        <tr>
                            <th style="width: 12%;"></th>
                            <th style="width: 12%;">FECHA REGISTRO</th>
                            <th style="width: 12%;">CIUDAD</th>
                            <th style="width: 12%;">PLACA</th>
                            <th style="width: 15%;"></th>
                            <th style="width: 15%;">CONSUMO DEL DIA</th>
                            <th style="width: 15%;"></th>
                            <th style="width: 9%;"></th>
                            <th style="width: 15%;"></th>
                            <th style="width: 15%;"></th>
                            <th style="width: 22%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $control = DB::select("select * from taller.fn_control_diario_adblue() where TO_CHAR(xfecha,'MM') = '$mes->xmes' order by xfecha asc"); ?>
                        @foreach ($control as $con)
                            <?php $detalle = DB::table('taller.vw_consumos')->select('cde_fecha', 'cde_qabastecida','est_id','cde_estado','veh_placa','est_descripcion')->where([['est_id',1],['cde_fecha','>=',$con->xcon_fecinicio],['cde_fecha','<=',$con->xcon_fecfin],['cde_estado',1]])->orderBy('cde_fecha','asc')->get(); ?>
                            <tr>
                                <td style="text-align: center;">{{ \Carbon\Carbon::parse($con->xfecha)->format('d/m/Y') }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: center;">{{ $con->xing_isotanque }}</td>
                                <td></td>
                                <td style="text-align: center;">{{ $con->xtotal_sal_isotanq }}</td>
                                <td style="text-align: center;">{{ $con->xstop }}</td>
                                <td style="text-align: center;">{{ $con->xexce_isotanq }}</td>
                                <td style="text-align: center;">{{ $con->xcantidad }}</td>
                                <td style="text-align: center; font-size: 0.7em;">{{ $con->xcon_observacion }}</td>
                                
                                <td style="border-bottom: 0px;border-top: 0px;"></td>
                                <td style="border-bottom: 0px;border-top: 0px;"></td>
                                <td style="border-bottom: 0px;border-top: 0px;"></td>
                            </tr>
                            @foreach ($detalle as $det)
                            <tr>
                                <td></td>
                                <td style="text-align: center;">{{ \Carbon\Carbon::parse($det->cde_fecha)->format('d/m/Y') }}</td>
                                <td style="text-align: center;">{{ $det->est_descripcion }}</td>
                                <td style="text-align: center;">{{ $det->veh_placa }}</td>
                                <td></td>
                                <td style="text-align: center;">{{$det->cde_qabastecida}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                
                                <td style="border-bottom: 0px;border-top: 0px;"></td>
                                <td style="border-bottom: 0px;border-top: 0px;"></td>
                                <td style="border-bottom: 0px;border-top: 0px;"></td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tr style="font-size: 1.4em;">
                        <td colspan="11" style="text-align: right;"><b> TOTAL: </b></td>
                        <td style="text-align: center;"><b>{{ $mes->xtot_desc_mensual }}</b></td>
                        <td style="text-align: center;"><b>{{ $mes->xtot_abast_mensual }}</b></td>
                        <td style="text-align: center;"><b>{{ $mes->xsaldo_prox_mes }}</b></td>
                    </tr>
                </table>
            @endforeach
        </div>
    </body>

</html>