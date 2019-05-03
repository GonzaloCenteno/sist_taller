<input type="hidden" value=" {{$num= 1}}">
<table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.0em;">
    <thead>
        <tr>
            <th rowspan="2" style="width: 5%;">N°</th>
            <th rowspan="2" style="width: 10%;">RUTA</th>  
            <th colspan="6" style="width: 85%;">COSTO OPTIMO GENERAL POR ABASTECIMIENTO EN RUTA</th>  
        </tr>
    </thead>
    <thead>
        <tr>  
            <th>CONSUMO DESEADO GENERAL</th>  
            <th>CONSUMO REAL AREQUIPA</th>  
            <th>TOTAL A/E</th>  
            <th>COSTO CDG</th>  
            <th>COSTO CRA</th>  
            <th>COSTO A/E</th>  
        </tr>
    </thead>
    <tbody>
        @foreach($datos as $dat)
        <tr>
            <td style="text-align: center;">{{ $num++ }}</td>
            <td style="text-align: center;">{{ $dat->xcde_ruta }}</td>
            <td style="text-align: center;">{{ $dat->cdg }}</td>
            <td style="text-align: center;">{{ $dat->cra }}</td>
            <td style="text-align: center;">{{ $dat->totalae }}</td>
            <td style="text-align: center;">S./ {{ $dat->costocdg }}</td>
            <td style="text-align: center;">S./ {{ $dat->costocra }}</td>
            <td style="text-align: center;">S./ {{ $dat->costoae }}</td>
        </tr>
        @endforeach
    </tbody>
    <tr>
        <td colspan="5" style="text-align: right;"><b> TOTAL: </b></td>
        <td style="text-align: center;"><b>S./ {{ $datos->sum('costocdg') }}</b></td>
        <td style="text-align: center;"><b>S./ {{ $datos->sum('costocra') }}</b></td>
        <td style="text-align: center;"><b>S./ {{ $datos->sum('costoae') }}</b></td>
    </tr>
</table>