<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>ABASTECIMIENTO IRIZAR</title>
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
                            <h1>CONTROL ABASTECIMIENTOS DE ADBLUE POR PLACA</h1>
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
        
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
            <thead>
                <tr>
                    <th>TOTAL N° VIAJES</th>
                    <th>TOTAL Q - ABAST.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($totales as $tot)
                <tr>
                    <td style="text-align: center;"><b> {{ $tot->count_vehid }} </b></td>
                    <td style="text-align: center;"><b> {{ $tot->sum_qabastecida }} </b></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table border="0" width="100%" cellpadding="5" cellspacing="5"> 
            <tr> 
                <td width="50%" style="border: inset 0pt"> 
                    @foreach($meses as $mes)
                        <?php $cab_datos = DB::table('taller.vw_rep_ctrl_abast_irizar')->select(DB::raw('distinct rut_id,rut_descripcion'))->where([['est_id',$mes->est_id],['veh_id',$mes->veh_id],['mes',$mes->mes]])->orderBy('rut_id','asc')->get(); ?>
                        <h2>RUTA: {{ $mes->est_descripcion }} - {{ strtoupper($mes->mes_descripcion) }} - {{ $mes->anio }}</h2> 
                        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
                            <thead>
                                <tr>
                                    <th colspan="{{ $cab_datos->count() + 1 }}" style="width: 50%;">N° VIAJES</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th colspan="{{ $cab_datos->count() + 1 }}" style="width: 50%;">{{ strtoupper($mes->mes_descripcion) }} - {{ $mes->anio }}</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th style="width: 20%;">PLACA</th>
                                    @foreach($cab_datos as $dat)
                                        <th style="width: 10%;">{{ $dat->rut_descripcion }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php   $enteros='';
                                        foreach ($cab_datos as $value_1){
                                            $enteros .=  $value_1->rut_descripcion.' INT,';
                                        }
                                        $var = trim($enteros,',');
                                ?>
                                <?php $nro_viajes = DB::select("SELECT * FROM CROSSTAB
                                                                (
                                                                    'select veh_placa,rut_descripcion,total 
                                                                    from taller.vw_rep_ctrl_abast_irizar 
                                                                    where mes = $$$mes->mes$$ and est_descripcion = $$$mes->est_descripcion$$ and veh_id = $mes->veh_id
                                                                    order by veh_placa,rut_id asc'
                                                                )AS contador (veh_placa text,$var)");?>
                                
                                <?php
                                foreach ( $nro_viajes as $viajes ) {
                                    ?>
                                    <tr>
                                    <?php
                                    foreach ( $viajes as $via ) {
                                    ?>
                                            <td style="text-align: center;"><?php echo isset($via) ? $via : 0;?></td>
                                    <?php
                                    }
                                    ?>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                                <?php $tot_nro_viajes = DB::select("select sum(total) as total,est_descripcion from taller.vw_rep_ctrl_abast_irizar where est_descripcion = '$mes->est_descripcion' and veh_id = '$mes->veh_id' and mes = '$mes->mes' group by est_descripcion order by est_descripcion");?>
                                @foreach($tot_nro_viajes as $totnro_viajes)
                                <tr>
                                    <td style="text-align: center;"><b> TOTAL: </b></td>
                                    <td colspan="{{ $cab_datos->count() }}" style="text-align: center;"><b>{{ $totnro_viajes->total }}</b></td>
                                </tr>
                                @endforeach
                        </table>
                    @endforeach
                </td> 
                <td width="50%" style="border: inset 0pt"> 
                    @foreach($meses as $mes)
                    <?php $det_datos = DB::table('taller.vw_rep_ctrl_abast_irizar')->select(DB::raw('distinct rut_id,rut_descripcion'))->where([['est_id',$mes->est_id],['veh_id',$mes->veh_id],['mes',$mes->mes]])->orderBy('rut_id','asc')->get(); ?>
                    <h2>RUTA: {{ $mes->est_descripcion }} - {{ strtoupper($mes->mes_descripcion) }} - {{ $mes->anio }}</h2> 
                        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.4em;">
                            <thead>
                                <tr>
                                    <th colspan="{{ $det_datos->count() + 1 }}" style="width: 50%;">Q - ABAST.</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th colspan="{{ $det_datos->count() + 1 }}" style="width: 50%;">{{ strtoupper($mes->mes_descripcion) }} - {{ $mes->anio }}</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th style="width: 20%;">PLACA</th>
                                    @foreach($det_datos as $dat)
                                        <th style="width: 12%;">{{ $dat->rut_descripcion }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <?php   $numeric='';
                                        foreach ($det_datos as $value_2){
                                            $numeric .=  $value_2->rut_descripcion.' numeric,';
                                        }
                                        $var_1 = trim($numeric,',');
                                ?>
                                <?php $q_abastecida = DB::select("SELECT * FROM CROSSTAB
                                                                (
                                                                    'select veh_placa,rut_descripcion,total 
                                                                    from taller.vw_rep_ctrl_abast_irizar_1
                                                                    where mes = $$$mes->mes$$ and est_descripcion = $$$mes->est_descripcion$$ and veh_id = $mes->veh_id
                                                                    order by veh_placa,rut_id asc'
                                                                )AS sumatoria (veh_placa text,$var_1)");?>
                                <?php
                                foreach ( $q_abastecida as $abastecida ) {
                                    ?>
                                    <tr>
                                    <?php
                                    foreach ( $abastecida as $abast ) {
                                    ?>
                                            <td style="text-align: center;"><?php echo isset($abast) ? $abast : 0.000;?></td>
                                    <?php
                                    }
                                    ?>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                                <?php $tot_sum_qabast = DB::select("select sum(total)::numeric(7,3) as total,est_descripcion from taller.vw_rep_ctrl_abast_irizar_1 where est_descripcion = '$mes->est_descripcion' and veh_id = '$mes->veh_id' and mes = '$mes->mes' group by est_descripcion order by est_descripcion"); ?>
                                @foreach($tot_sum_qabast as $totsum_qabast)
                                <tr>
                                    <td style="text-align: center;"><b> TOTAL: </b></td>
                                    <td colspan="{{ $det_datos->count() }}" style="text-align: center;"><b>{{ $totsum_qabast->total }}</b></td>
                                </tr>
                                @endforeach
                        </table>
                    @endforeach
                </td>
            </tr> 
        </table> 

    </body>

</html>