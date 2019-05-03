<input type="hidden" value=" {{$num= 1}}">
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
