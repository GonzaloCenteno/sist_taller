<input type="hidden" value=" {{$num= 1}}">
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