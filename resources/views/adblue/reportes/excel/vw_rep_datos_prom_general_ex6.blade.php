<input type="hidden" value=" {{$num= 1}}">
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
            <?php $costo_ahorro += $dat->tot; ?>
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
            <?php $costo_exceso += $dat->tot; ?>
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