<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>DATOS PROMEDIOS GENERALES POR TODA LA RUTA</title>
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
                            <h1>DATOS PROMEDIOS GENERALES POR TODA LA RUTA</h1>
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
        
        <input type="hidden" value=" {{$num= 1}}">

        <div class="lado3" style="height: 435px; margin-bottom: 20px;">
            <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.0em;">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 5%;">N°</th>
                        <th rowspan="2" style="width: 10%;">RUTA</th>  
                        <th colspan="7" style="width: 85%;">DATOS PROMEDIOS GENERALES POR TODA LA RUTA</th>  
                    </tr>
                </thead>
                <thead>
                    <tr>  
                        <th>CONSUMO</th>  
                        <th>KILOMETRAJE</th>  
                        <th>RENDIMIENTO</th>  
                        <th>AHORRO</th>  
                        <th>EXCESO</th>  
                        <th>TOTAL A/E</th>  
                        <th>N° VIAJES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $dat)
                        <tr>
                            <td style="text-align: center;">{{ $num++ }}</td>
                            <td style="text-align: center;">{{ $dat->xcde_ruta }}</td>
                            <td style="text-align: center;">{{ $dat->consumo }}</td>
                            <td style="text-align: center;">{{ $dat->kg }}</td>
                            <td style="text-align: center;">{{ $dat->rendimiento }}</td>
                            <td style="text-align: center;">{{ $dat->ahorro }}</td>
                            <td style="text-align: center;">{{ $dat->exceso }}</td>
                            <td style="text-align: center;">{{ $dat->totalae }}</td>
                            <td style="text-align: center;">{{ $dat->nro_viajes }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tr>
                    <td colspan="8" style="text-align: right;"><b> TOTAL: </b></td>
                    <td style="text-align: center;"><b>{{ $datos->sum('nro_viajes') }}</b></td>
                </tr>
            </table>
        </div>
    </body>

</html>