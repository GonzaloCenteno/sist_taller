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
        
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
            <thead>
                <tr>
                    <th>TOTAL N° VIAJES</th>
                    <th>TOTAL Q - ABAST.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($totales as $tot)
                <tr>
                    <td style="text-align: center;"><b> {{ $tot->count_vehid }} </b></td>
                    <td style="text-align: center;"><b> {{ $tot->sum_qabastecida }} </b></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table border="0" width="100%" cellpadding="5" cellspacing="5"> 
            <tr> 
                <td width="50%" style="border: inset 0pt"> 
                    @foreach($meses as $mes)
                        <h2>RUTA: {{ $mes->est_descripcion }}</h2> 
                        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
                            <thead>
                                <tr>
                                    <th colspan="20" style="width: 50%;">N° VIAJES</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th colspan="20" style="width: 50%;">{{ strtoupper($mes->mes_descripcion) }} - {{ $mes->anio }}</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th style="width: 20%;">PLACA</th>
                                    <th style="width: 10%;">R1</th>
                                    <th style="width: 10%;">R2</th>
                                    <th style="width: 10%;">R3</th>
                                    <th style="width: 10%;">R4</th>
                                    <th style="width: 10%;">R5</th>
                                    <th style="width: 10%;">R6</th>
                                    <th style="width: 10%;">R7</th>
                                    <th style="width: 10%;">R8</th>
                                    <th style="width: 10%;">R9</th>
                                    <th style="width: 10%;">R10</th>
                                    <th style="width: 10%;">R11</th>
                                    <th style="width: 10%;">R12</th>
                                    <th style="width: 10%;">R13</th>
                                    <th style="width: 10%;">R14</th>
                                    <th style="width: 10%;">R15</th>
                                    <th style="width: 10%;">R16</th>
                                    <th style="width: 10%;">R17</th>
                                    <th style="width: 10%;">R18</th>
                                    <th style="width: 10%;">OTR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $nro_viajes = DB::select("SELECT est_descripcion,veh_placa,
                                                                SUM(CASE rut_descripcion WHEN 'R1' THEN total ELSE 0 END) AS R1,SUM(CASE rut_descripcion WHEN 'R2' THEN total ELSE 0 END) AS R2,
                                                                SUM(CASE rut_descripcion WHEN 'R3' THEN total ELSE 0 END) AS R3,SUM(CASE rut_descripcion WHEN 'R4' THEN total ELSE 0 END) AS R4,
                                                                SUM(CASE rut_descripcion WHEN 'R5' THEN total ELSE 0 END) AS R5,SUM(CASE rut_descripcion WHEN 'R6' THEN total ELSE 0 END) AS R6,
                                                                SUM(CASE rut_descripcion WHEN 'R7' THEN total ELSE 0 END) AS R7,SUM(CASE rut_descripcion WHEN 'R8' THEN total ELSE 0 END) AS R8,
                                                                SUM(CASE rut_descripcion WHEN 'R9' THEN total ELSE 0 END) AS R9,SUM(CASE rut_descripcion WHEN 'R10' THEN total ELSE 0 END) AS R10,
                                                                SUM(CASE rut_descripcion WHEN 'R11' THEN total ELSE 0 END) AS R11,SUM(CASE rut_descripcion WHEN 'R12' THEN total ELSE 0 END) AS R12,
                                                                SUM(CASE rut_descripcion WHEN 'R13' THEN total ELSE 0 END) AS R13,SUM(CASE rut_descripcion WHEN 'R14' THEN total ELSE 0 END) AS R14,
                                                                SUM(CASE rut_descripcion WHEN 'R14' THEN total ELSE 0 END) AS R14,SUM(CASE rut_descripcion WHEN 'R15' THEN total ELSE 0 END) AS R15,
                                                                SUM(CASE rut_descripcion WHEN 'R16' THEN total ELSE 0 END) AS R16,SUM(CASE rut_descripcion WHEN 'R17' THEN total ELSE 0 END) AS R17,
                                                                SUM(CASE rut_descripcion WHEN 'R18' THEN total ELSE 0 END) AS R18,SUM(CASE rut_descripcion WHEN 'OTR' THEN total ELSE 0 END) AS OTR
                                                                FROM taller.vw_rep_ctrl_abast_irizar 
                                                                where mes = '$mes->mes' and est_descripcion = '$mes->est_descripcion' and veh_id = '$mes->veh_id'
                                                                GROUP BY est_descripcion,veh_placa ORDER BY veh_placa asc");?>
                                @foreach($nro_viajes as $viajes)
                                <tr>
                                    <td style="text-align: center;">{{ $viajes->veh_placa }}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r1) ? $viajes->r1 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r2) ? $viajes->r2 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r3) ? $viajes->r3 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r4) ? $viajes->r4 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r5) ? $viajes->r5 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r6) ? $viajes->r6 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r7) ? $viajes->r7 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r8) ? $viajes->r8 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r9) ? $viajes->r9 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r10) ? $viajes->r10 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r11) ? $viajes->r11 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r12) ? $viajes->r12 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r13) ? $viajes->r13 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r14) ? $viajes->r14 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r15) ? $viajes->r15 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r16) ? $viajes->r16 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r17) ? $viajes->r17 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->r18) ? $viajes->r18 : 0}}</td>
                                    <td style="text-align: center;">{{ isset($viajes->otr) ? $viajes->otr : 0}}</td>
                                </tr>
                                @endforeach  
                            </tbody>
                                <?php $tot_nro_viajes = DB::select("select sum(total) as total,est_descripcion from taller.vw_rep_ctrl_abast_irizar where est_descripcion = '$mes->est_descripcion' and veh_id = '$mes->veh_id' and mes = '$mes->mes' group by est_descripcion order by est_descripcion");?>
                                @foreach($tot_nro_viajes as $totnro_viajes)
                                <tr>
                                    <td style="text-align: center;"><b> TOTAL: </b></td>
                                    <td colspan="19" style="text-align: center;"><b>{{ $totnro_viajes->total }}</b></td>
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
                                    <th colspan="19" style="width: 50%;">Q - ABAST.</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th colspan="19" style="width: 50%;">{{ strtoupper($mes->mes_descripcion) }} - {{ $mes->anio }}</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th style="width: 12%;">R1</th>
                                    <th style="width: 12%;">R2</th>
                                    <th style="width: 12%;">R3</th>
                                    <th style="width: 12%;">R4</th>
                                    <th style="width: 12%;">R5</th>
                                    <th style="width: 12%;">R6</th>
                                    <th style="width: 12%;">R7</th>
                                    <th style="width: 12%;">R8</th>
                                    <th style="width: 12%;">R9</th>
                                    <th style="width: 12%;">R10</th>
                                    <th style="width: 12%;">R11</th>
                                    <th style="width: 12%;">R12</th>
                                    <th style="width: 12%;">R13</th>
                                    <th style="width: 12%;">R14</th>
                                    <th style="width: 12%;">R15</th>
                                    <th style="width: 12%;">R16</th>
                                    <th style="width: 12%;">R17</th>
                                    <th style="width: 12%;">R18</th>
                                    <th style="width: 12%;">OTR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $q_abastecida = DB::select("SELECT est_descripcion,veh_placa,
                                                                SUM(CASE rut_descripcion WHEN 'R1' THEN total ELSE 0.000 END) AS R1,SUM(CASE rut_descripcion WHEN 'R2' THEN total ELSE 0.000 END) AS R2,
                                                                SUM(CASE rut_descripcion WHEN 'R3' THEN total ELSE 0.000 END) AS R3,SUM(CASE rut_descripcion WHEN 'R4' THEN total ELSE 0.000 END) AS R4,
                                                                SUM(CASE rut_descripcion WHEN 'R5' THEN total ELSE 0.000 END) AS R5,SUM(CASE rut_descripcion WHEN 'R6' THEN total ELSE 0.000 END) AS R6,
                                                                SUM(CASE rut_descripcion WHEN 'R7' THEN total ELSE 0.000 END) AS R7,SUM(CASE rut_descripcion WHEN 'R8' THEN total ELSE 0.000 END) AS R8,
                                                                SUM(CASE rut_descripcion WHEN 'R9' THEN total ELSE 0.000 END) AS R9,SUM(CASE rut_descripcion WHEN 'R10' THEN total ELSE 0.000 END) AS R10,
                                                                SUM(CASE rut_descripcion WHEN 'R11' THEN total ELSE 0.000 END) AS R11,SUM(CASE rut_descripcion WHEN 'R12' THEN total ELSE 0.000 END) AS R12,
                                                                SUM(CASE rut_descripcion WHEN 'R13' THEN total ELSE 0.000 END) AS R13,SUM(CASE rut_descripcion WHEN 'R14' THEN total ELSE 0.000 END) AS R14,
                                                                SUM(CASE rut_descripcion WHEN 'R14' THEN total ELSE 0.000 END) AS R14,SUM(CASE rut_descripcion WHEN 'R15' THEN total ELSE 0.000 END) AS R15,
                                                                SUM(CASE rut_descripcion WHEN 'R16' THEN total ELSE 0.000 END) AS R16,SUM(CASE rut_descripcion WHEN 'R17' THEN total ELSE 0.000 END) AS R17,
                                                                SUM(CASE rut_descripcion WHEN 'R18' THEN total ELSE 0.000 END) AS R18,SUM(CASE rut_descripcion WHEN 'OTR' THEN total ELSE 0.000 END) AS OTR
                                                                FROM taller.vw_rep_ctrl_abast_irizar_1 
                                                                where mes = '$mes->mes' and est_descripcion = '$mes->est_descripcion' and veh_id = '$mes->veh_id'
                                                                GROUP BY est_descripcion,veh_placa ORDER BY veh_placa asc");?>
                                @foreach($q_abastecida as $q_abast)
                                <tr>
                                    <td style="text-align: center;">{{ isset($q_abast->r1) ? $q_abast->r1 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r2) ? $q_abast->r2 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r3) ? $q_abast->r3 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r4) ? $q_abast->r4 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r5) ? $q_abast->r5 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r6) ? $q_abast->r6 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r7) ? $q_abast->r7 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r8) ? $q_abast->r8 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r9) ? $q_abast->r9 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r10) ? $q_abast->r10 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r11) ? $q_abast->r11 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r12) ? $q_abast->r12 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r13) ? $q_abast->r13 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r14) ? $q_abast->r14 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r15) ? $q_abast->r15 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r16) ? $q_abast->r16 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r17) ? $q_abast->r17 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->r18) ? $q_abast->r18 : 0.000}}</td>
                                    <td style="text-align: center;">{{ isset($q_abast->otr) ? $q_abast->otr : 0.000}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                                <?php $tot_sum_qabast = DB::select("select sum(total)::numeric(7,3) as total,est_descripcion from taller.vw_rep_ctrl_abast_irizar_1 where est_descripcion = '$mes->est_descripcion' and veh_id = '$mes->veh_id' and mes = '$mes->mes' group by est_descripcion order by est_descripcion"); ?>
                                @foreach($tot_sum_qabast as $totsum_qabast)
                                <tr>
                                    <td colspan="19" style="text-align: center;"><b>{{ $totsum_qabast->total }}</b></td>
                                </tr>
                                @endforeach
                        </table>
                    @endforeach
                </td> 
            </tr> 
        </table> 

    </body>

</html>