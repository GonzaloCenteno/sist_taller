<style>
    table {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
        padding-top: 3px;
        padding-bottom: 3px;
    }
    table th {
        font-weight: bold;
        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        color: #ffffff;
        background-color: #C00000;
        border:1px solid #000;
        font-size:0.8em;
    }
    table td {
        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
        border:1px solid #000;
        font-size:0.7em;
    }
    
    h2 {
        text-align: center;
    }
</style>
<h2>CONSUMOS EN GENERAL</h2>
<table>
    <thead style="width: 100%;">
        <tr>
            <th style="text-align: center; width: 7%;">VALE</th>
            <th style="text-align: center; width: 7%;">PLACA</th>
            <th style="text-align: center; width: 8%;">DNI</th>
            <th style="text-align: center; width: 26%;">CONDUCTOR</th>
            <th style="text-align: center; width: 8%;">FECHA</th>
            <th style="text-align: center; width: 6%;">HORA</th>
            <th style="text-align: center; width: 11%;">ESTACION</th>
            <th style="text-align: center; width: 10%;">KILOMETRAJE</th>
            <th style="text-align: center; width: 9%;">BOMBA</th>
            <th style="text-align: center; width: 8%;">CANTIDAD</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datos as $dat)
            <tr>
                <td style="text-align: center; width: 7%;">{{ $dat->vca_numvale }}</td>
                <td style="text-align: center; width: 7%;">{{ $dat->veh_placa }}</td>
                <td style="text-align: center; width: 8%;">{{ $dat->tri_nrodoc }}</td>
                <td style="text-align: left; width: 26%;">{{ $dat->tripulante }}</td>
                <td style="text-align: center; width: 8%;">{{ \Carbon\Carbon::parse($dat->vca_fecha)->format('d/m/Y') }}</td>
                <td style="text-align: center; width: 6%;">{{ $dat->vca_hora }}</td>
                <td style="text-align: left; width: 11%;">{{ $dat->est_descripcion }}</td>
                <td style="text-align: center; width: 10%;">{{ $dat->vca_kilometraje }}</td>
                <td style="text-align: left; width: 9%;">{{ $dat->bom_descripcion }}</td>
                <td style="text-align: center; width: 8%;">{{ $dat->vde_cantidad }}</td>
            </tr>
        @endforeach
    </tbody>
</table>