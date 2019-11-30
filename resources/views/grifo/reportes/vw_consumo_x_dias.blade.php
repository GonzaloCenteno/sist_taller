<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>CONSUMO EN GENERAL</title>
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
                            <h1>CONSUMO EN GENERAL</h1>
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
            <table border="0" cellspacing="0" cellpadding="0" style="font-size: 1.4em; float: right;clear: both;">
                <thead>
                    <tr>
                        <th>VALE</th>
                        <th>PLACA</th>
                        <th>DNI</th>
                        <th>CONDUCTOR</th>
                        <th>FECHA</th>
                        <th>HORA</th>
                        <th>ESTACION</th>
                        <th>KILOMETRAJE</th>
                        <th>BOMBA</th>
                        <th>CANTIDAD</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $dat)
                        <tr>
                            <td style="text-align: center;">{{ $dat->vca_numvale }}</td>
                            <td style="text-align: center;">{{ $dat->veh_placa }}</td>
                            <td style="text-align: center;">{{ $dat->tri_nrodoc }}</td>
                            <td style="text-align: left;">{{ $dat->tripulante }}</td>
                            <td style="text-align: center;">{{ \Carbon\Carbon::parse($dat->vca_fecha)->format('d/m/Y') }}</td>
                            <td style="text-align: center;">{{ $dat->vca_hora }}</td>
                            <td style="text-align: left;">{{ $dat->est_descripcion }}</td>
                            <td style="text-align: center;">{{ $dat->vca_kilometraje }}</td>
                            <td style="text-align: left;">{{ $dat->bom_descripcion }}</td>
                            <td style="text-align: center;">{{ $dat->vde_cantidad }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>

</html>