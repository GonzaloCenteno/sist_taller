<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>ABASTECIMIENTO IRIZAR</title>
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
                            <h1>CONTROL ABASTECIMIENTOS DE ADBLUE POR PLACA</h1>
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

        <table border="0" width="100%" cellpadding="5" cellspacing="5"> 
            <tr> 
                <td width="50%" style="border: inset 0pt"> 
                    @foreach($meses as $mes)
                        <h2>RUTA: {{ $mes->est_descripcion }}</h2> 
                        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
                            <thead>
                                <tr>
                                    <th colspan="5" style="width: 50%;">N° VIAJES</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th colspan="5" style="width: 50%;">{{ strtoupper($mes->mes_descripcion) }} - {{ $mes->anio }}</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th style="width: 20%;">PLACA</th>
                                    <th style="width: 10%;">R5</th>
                                    <th style="width: 10%;">R4</th>
                                    <th style="width: 10%;">R1</th>
                                    <th style="width: 10%;">OTR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $nro_viajes = DB::select("SELECT est_descripcion,veh_placa,SUM(CASE rut_descripcion WHEN 'R5' THEN total ELSE 0 END) AS R5,SUM(CASE rut_descripcion WHEN 'R4' THEN total ELSE 0 END) AS R4,SUM(CASE rut_descripcion WHEN 'R1' THEN total ELSE 0 END) AS R1,SUM(CASE rut_descripcion WHEN 'OTR' THEN total ELSE 0 END) AS OTR FROM taller.vw_rep_ctrl_abast_irizar where mes = '$mes->mes' and est_descripcion = '$mes->est_descripcion' GROUP BY est_descripcion,veh_placa ORDER BY veh_placa asc");?>
                                @foreach($nro_viajes as $viajes)
                                <tr>
                                    <td style="text-align: center;">{{ $viajes->veh_placa }}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r5) ? $viajes->r5 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r4) ? $viajes->r4 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r1) ? $viajes->r1 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->otr) ? $viajes->otr : 0}}</td>
                                </tr>
                                @endforeach  
                            </tbody>
                                <?php $tot_nro_viajes = DB::select("select sum(total) as total,est_descripcion from taller.vw_rep_ctrl_abast_irizar where est_descripcion = '$mes->est_descripcion' group by est_descripcion order by est_descripcion");?>
                                @foreach($tot_nro_viajes as $totnro_viajes)
                                <tr>
                                    <td style="text-align: center;"><b> TOTAL: </b></td>
                                    <td colspan="4" style="text-align: center;"><b>{{ $totnro_viajes->total }}</b></td>
                                </tr>
                                @endforeach
                        </table>
                    @endforeach
                </td> 
                <td width="50%" style="border: inset 0pt"> 
                    @foreach($meses as $mes)
                    <h2>RUTA: {{ $mes->est_descripcion }}</h2> 
                        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
                            <thead>
                                <tr>
                                    <th colspan="4" style="width: 50%;">Q - ABAST.</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th colspan="4" style="width: 50%;">{{ strtoupper($mes->mes_descripcion) }} - {{ $mes->anio }}</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th style="width: 10%;">R5</th>
                                    <th style="width: 10%;">R4</th>
                                    <th style="width: 10%;">R1</th>
                                    <th style="width: 10%;">OTR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $q_abastecida = DB::select("SELECT est_descripcion,veh_placa,SUM(CASE rut_descripcion WHEN 'R5' THEN total ELSE 0.000 END) AS R5,SUM(CASE rut_descripcion WHEN 'R4' THEN total ELSE 0.000 END) AS R4,SUM(CASE rut_descripcion WHEN 'R1' THEN total ELSE 0.000 END) AS R1,SUM(CASE rut_descripcion WHEN 'OTR' THEN total ELSE 0.000 END) AS OTR FROM taller.vw_rep_ctrl_abast_irizar_1 where mes = '$mes->mes' and est_descripcion = '$mes->est_descripcion' GROUP BY est_descripcion,veh_placa ORDER BY veh_placa asc");?>
                                @foreach($q_abastecida as $q_abast)
                                <tr>
                                    <td style="text-align: center;">{{ isset($q_abast->r5) ? $q_abast->r5 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r4) ? $q_abast->r4 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r1) ? $q_abast->r1 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->otr) ? $q_abast->otr : 0}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                                <?php $tot_sum_qabast = DB::select("select sum(total)::numeric(7,3) as total,est_descripcion from taller.vw_rep_ctrl_abast_irizar_1 where est_descripcion = '$mes->est_descripcion' group by est_descripcion order by est_descripcion"); ?>
                                @foreach($tot_sum_qabast as $totsum_qabast)
                                <tr>
                                    <td colspan="4" style="text-align: center;"><b>{{ $totsum_qabast->total }}</b></td>
                                </tr>
                                @endforeach
                        </table>
                    @endforeach
                </td> 
            </tr> 
        </table> 

    </body>

</html>