<table border="2" style="text-align:center;font-size: 2em;">
    <thead>
        <tr style="font-size: 0.9em;">	
            <td style="width: 5%; text-align: center;"><b>Nº</b></td>
            <td style="width: 25%; text-align: center;"><b>VAL</b></td>
            <td style="width: 2%; text-align: center;"><b>REGISTRO</b></td>
            <td style="width: 25%; text-align: center;"><b>ESTADO</b></td>
        </tr>
    </thead>
    <tbody>

        @foreach ($datos as $dat)
        <tr style="font-size: 0.8em;">
            <td style="text-align: left;">{{ $dat->cap_id }}</td>
            <td style="text-align: left;">{{ $dat->cap_val }}</td>
            <td style="text-align: left;">{{ $dat->cap_fecregistro }}</td>
            <td style="text-align: left;">{{ $dat->cap_estado }}</td>
        </tr>
        @endforeach

    </tbody>
</table>
