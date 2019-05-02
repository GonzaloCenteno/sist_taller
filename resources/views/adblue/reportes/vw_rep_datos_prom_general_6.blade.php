<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>COSTO GENERAL POR ABASTECIMIENTO POR PLACA</title>
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
                            <h1>COSTO GENERAL POR ABASTECIMIENTO POR PLACA</h1>
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
                        <th rowspan="2" style="width: 10%;">PLACA</th>  
                        <th colspan="4" style="width: 85%;">COSTO GENERAL POR ABASTECIMIENTO POR PLACA</th>  
                    </tr>
                </thead>
                <thead>
                    <tr>  
                        <th>AHORRO</th>  
                        <th>COSTO AHORRO</th>  
                        <th>EXCESO</th>  
                        <th>COSTO EXCESO</th>  
                    </tr>
                </thead>
                <tbody>
                    <?php $costo_ahorro = 0; ?>
                    <?php $costo_exceso = 0; ?>
                    @foreach($datos as $dat)
                        <tr>
                            <td style="text-align: center;">{{ $num++ }}</td>
                            <td style="text-align: center;">{{ $dat->xcde_placa }}</td>
                            @if($dat->sum > 0)
                                <td style="text-align: center;">{{ $dat->sum }}</td>
                            @else
                                <td style="text-align: center;">0.000</td>
                            @endif
                            
                            @if($dat->tot > 0)
                                <?php $costo_ahorro += $dat->tot;?>
                                <td style="text-align: center;">S/. {{ $dat->tot }}</td>
                            @else
                                <td style="text-align: center;">S/. 0.000</td>
                            @endif
                            
                            @if($dat->sum < 0)
                                <td style="text-align: center;">{{ $dat->sum }}</td>
                            @else
                                <td style="text-align: center;">0.000</td>
                            @endif
                            
                            @if($dat->tot < 0)
                                <?php $costo_exceso += $dat->tot;?>
                                <td style="text-align: center;">S/. {{ $dat->tot }}</td>
                            @else
                                <td style="text-align: center;">S/. 0.000</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                <tr>
                    <td colspan="3" style="text-align: right;"><b> TOTAL: </b></td>
                    <td style="text-align: center;"><b>S./ {{ $costo_ahorro }}</b></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"><b>S./ {{ $costo_exceso }}</b></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><b> COSTO TOTAL DE AHORRO / EXCESO: </b></td>
                    <td colspan="3" style="text-align: center;"><b>S./ {{ $costo_ahorro + $costo_exceso}}</b></td>
                </tr>
            </table>
        </div>
    </body>

</html>