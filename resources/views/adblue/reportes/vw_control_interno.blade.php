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
                <h2>{{ $mes->mes_descripcion }}</h2> 
                <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
                    <thead>
                        <tr >
                            <th style="width: 20%;">FECHA INGRESO</th>
                            <th style="width: 15%;"></th>
                            <th style="width: 15%;">INGRESO</th>
                            <th style="width: 15%;"></th>
                            <th style="width: 15%;">TOTAL SALIDA</th>
                            <th style="width: 15%;">STOP</th>
                            <th style="width: 15%;">CANTIDAD</th>
                            <th rowspan="2" style="width: 15%;">TOTAL INGREO MENSUAL</th>
                            <th rowspan="2" style="width: 15%;">TOTAL SALIDA MENSUAL</th>
                            <th rowspan="2" style="width: 15%;">EXISTENCIA</th>
                            
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th style="width: 15%;"></th>
                            <th style="width: 20%;">FECHA REGISTRO</th>
                            <th style="width: 15%;"></th>
                            <th style="width: 15%;">SALIDA</th>
                            <th style="width: 15%;"></th>
                            <th style="width: 15%;"></th>
                            <th style="width: 15%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $control = DB::table('taller.tblcontrol_con')->where(DB::raw("TO_CHAR(con_fecregistro,'MM')"),$mes->mes)->orderBy('con_fecregistro','asc')->get(); ?>
                        @foreach ($control as $con)
                            <?php $detalle = DB::table('taller.tblconsumodetalle_cde')->select('cde_fecha', 'cde_qabastecida','est_id','cde_estado')->where([['est_id',1],['cde_fecha','>',$con->con_fecinicio],['cde_fecha','<=',$con->con_fecfin],['cde_estado',1]])->orderBy('cde_fecha','asc')->get(); ?>
                            
                            @foreach ($detalle as $det)
                            <tr>
                                <td></td>
                                <td style="text-align: center;">{{ \Carbon\Carbon::parse($det->cde_fecha)->format('d/m/Y') }}</td>
                                <td></td>
                                <td style="text-align: center;">{{$det->cde_qabastecida}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td style="text-align: center;">{{ \Carbon\Carbon::parse($con->con_fecregistro)->format('d/m/Y') }}</td>
                                <td></td>
                                <td style="text-align: center;">{{ $con->con_ingreso }}</td>
                                <td></td>
                                <td style="text-align: center;">{{ $con->con_totsalida }}</td>
                                <td style="text-align: center;">{{ $con->con_stop }}</td>
                                <td style="text-align: center;">{{ $con->con_cantidad }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tr>
                        <td colspan="7" style="text-align: right;"><b> TOTAL: </b></td>
                        <td style="text-align: center;"><b>{{ $mes->cantidad }}</b></td>
                        <td style="text-align: center;"><b>{{ $mes->totsalida }}</b></td>
                        <td style="text-align: center;"><b>{{ $mes->existencia }}</b></td>
                    </tr>
                </table>
            @endforeach
        </div>
    </body>

</html>