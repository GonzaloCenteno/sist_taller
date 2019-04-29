<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>CONTROL CONSUMOS</title>
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
                            <h1>CONTROL CONSUMOS</h1>
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
        
        <h2>{{ strtoupper($mes) }}</h2> 
        
        <table border="0" width="120%" cellpadding="5" cellspacing="5" style="height: 200px;"> 
            <tr> 
                <td width="10%" style="border: inset 0pt;">      
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 1.4em; float: right;clear: both;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 12%;">RUTAS</th>
                                <th colspan="6" style="width: 88%;">TOTAL</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <th>CONSUMO</th>
                                <th>KM.</th>
                                <th>RENDIMIENTO</th>
                                <th>AHORRO</th>
                                <th>EXCESO</th>
                                <th>TOTAL A/E</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rutas as $rut)
                                <tr>
                                    <td style="text-align: center;">{{ $rut->xcde_ruta }}</td>
                                    <td style="text-align: center;">{{ $rut->consumo }}</td>
                                    <td style="text-align: center;">{{ $rut->kg }}</td>
                                    <td style="text-align: center;">{{ $rut->rendimiento }}</td>
                                    <td style="text-align: center;">{{ $rut->ahorro }}</td>
                                    <td style="text-align: center;">{{ $rut->exceso }}</td>
                                    <td style="text-align: center;">{{ $rut->totalae }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td width="10%" style="border: inset 0pt;">      
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 1.4em; float: right;clear: both;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 15%;">PLACAS</th>
                                <th colspan="4" style="width: 85%;">TOTAL</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <th>RENDIMIENTO</th>
                                <th>AHORRO</th>
                                <th>EXCESO</th>
                                <th>TOTAL A/E</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($placas as $pla)
                                <tr>
                                    <td style="text-align: center;">{{ $pla->xcde_placa }}</td>
                                    <td style="text-align: center;">{{ $pla->rendimiento }}</td>
                                    <td style="text-align: center;">{{ $pla->ahorro }}</td>
                                    <td style="text-align: center;">{{ $pla->exceso }}</td>
                                    <td style="text-align: center;">{{ $pla->totalae }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr> 
        </table>
    </body>
</html>