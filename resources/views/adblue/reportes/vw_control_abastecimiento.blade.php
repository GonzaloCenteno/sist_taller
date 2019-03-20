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

        <div class="lado3" style="height: 435px; margin-bottom: 20px;">
            @foreach($meses as $mes)
                <h2>{{ strtoupper($mes->mes_descripcion) }}</h2> 
                <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
                    <thead>
                        <tr>
                            <th style="width: 25%;">DESTINO</th>
                            <th style="width: 20%;">N° VIAJES</th>
                            <th style="width: 20%;">Q-ABAST.</th>
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
            @endforeach
        </div>
    </body>

</html>