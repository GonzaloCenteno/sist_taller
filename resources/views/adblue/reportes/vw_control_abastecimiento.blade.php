<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>CONTROL ABASTECIMIENTO</title>
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
                            <h1>CONTROL ABASTECIMIENTOS DE ADBLUE POR CIUDAD - LITROS</h1>
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
        
        <table border="0" width="120%" cellpadding="5" cellspacing="5" style="height: 200px;"> 
            <tr> 
                @foreach($meses as $mes)
                <td width="10%" style="border: inset 0pt;">      
                <table border="0" cellspacing="0" cellpadding="0" style="font-size: 1.4em; float: right;clear: both;">
                    <thead>
                        <tr>
                            <th colspan="3" style="width: 10%;">{{ strtoupper($mes->mes_descripcion) }}</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>DESTINO</th>
                            <th>N° VIAJES</th>
                            <th>Q-ABAST.</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $control = DB::select("select est_descripcion,count(est_id) as viajes,sum(cde_qabastecida)::numeric(7,3) as qabast,mes from taller.vw_rep_ctrl_abastecimiento where mes = '$mes->mes' group by mes,est_descripcion order by est_descripcion"); ?>    
                    @foreach($control as $con)
                        <tr>
                            <td style="text-align: center;">{{ $con->est_descripcion }}</td>
                            <td style="text-align: center;">{{ $con->viajes }}</td>
                            <td style="text-align: center;">{{ $con->qabast }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tr>
                        <td style="text-align: right;"><b> TOTAL: </b></td>
                        <td style="text-align: center;"><b>{{ $mes->tot_nroviajes }}</b></td>
                        <td style="text-align: center;"><b>{{ $mes->tot_qabastecida }}</b></td>
                    </tr>
                </table>
                </td> 
                @endforeach
            </tr> 
        </table>
    </body>

</html>