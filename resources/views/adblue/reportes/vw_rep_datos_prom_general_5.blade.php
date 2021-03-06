<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>COSTO GENERAL  POR ABASTECIMIENTO EN RUTA</title>
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
                            <h1>COSTO GENERAL  POR ABASTECIMIENTO EN RUTA</h1>
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
                        <th colspan="5" style="width: 85%;">COSTO GENERAL  POR ABASTECIMIENTO EN RUTA</th>  
                    </tr>
                </thead>
                <thead>
                    <tr>  
                        <th>AHORRO</th>  
                        <th>TOTAL C/A</th>  
                        <th>EXCESO</th>  
                        <th>TOTAL C/E</th>  
                        <th>TOTAL C/AE</th>  
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $dat)
                        <tr>
                            <td style="text-align: center;">{{ $num++ }}</td>
                            <td style="text-align: center;">{{ $dat->xcde_ruta }}</td>
                            <td style="text-align: center;">{{ $dat->ahorro }}</td>
                            <td style="text-align: center;">S/. {{ $dat->totalca }}</td>
                            <td style="text-align: center;">{{ $dat->exceso }}</td>
                            <td style="text-align: center;">S/. {{ $dat->totalce }}</td>
                            <td style="text-align: center;">S/. {{ $dat->totalcae }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tr>
                    <td colspan="3" style="text-align: right;"><b> TOTAL: </b></td>
                    <td style="text-align: center;"><b>S./ {{ $datos->sum('totalca') }}</b></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"><b>S./ {{ $datos->sum('totalce') }}</b></td>
                    <td style="text-align: center;"><b>S./ {{ $datos->sum('totalcae') }}</b></td>
                </tr>
            </table>
        </div>
    </body>

</html>