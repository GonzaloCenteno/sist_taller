<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class Control_Controller extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu_registro = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',1]])->orderBy('menu_id','asc')->get();
            return view('adblue/vw_control',compact('menu_registro'));
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }

    public function show($id, Request $request)
    {
        if ($id > 0) 
        {
            
        }
        else
        {
            if($request['grid'] == 'control')
            {
                return $this->crear_tabla_control($request);
            }
            if($request['busqueda'] == 'placas')
            {
                return $this->autocompletar_placas($request);
            }
        }
    }

    public function create(Request $request)
    {
        $function = DB::select("select taller.control_salida(1,".round($request['cantidad'],3).",'".strtoupper($request['observacion'])."')");
        return $function;
    }

    public function edit($est_id,Request $request)
    {
        
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }
    
    public function autocompletar_placas(Request $request)
    {
        $Consulta = DB::table('taller.tblvehiculos_veh')->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->veh_id;
            $Lista->label = strtoupper(trim($Datos->veh_placa));
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
    
    public function crear_tabla_control(Request $request)
    {
        header('Content-type: application/json');
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $start = ($limit * $page) - $limit;  
        if ($start < 0) {
            $start = 0;
        }
        
        $totalg = DB::select("select count(*) as total from taller.fn_control_diario_adblue()");
        $sql = DB::select("select * from taller.fn_control_diario_adblue() order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);

        $total_pages = 0;
        if (!$sidx) {
            $sidx = 1;
        }
        $count = $totalg[0]->total;
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->xcon_id;           
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->xcon_id),
                trim($Datos->xfecha),
                trim($Datos->xing_isotanque),
                trim($Datos->xtotal_sal_isotanq),
                trim($Datos->xstop),
                trim($Datos->xexce_isotanq),
                trim($Datos->xcantidad),
                trim($Datos->xcon_observacion),
            );
        }
        return response()->json($Lista);
    }
    
    public function abrir_rep_control_diario(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('menu_rol') == 6)
        {
            $meses = DB::select("select * from taller.fn_control_diario_adblue_total()");
            if(count($meses) > 0)
            {
                $view = \View::make('adblue.reportes.vw_control_interno',compact('meses'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4','landscape');
                return $pdf->stream("CONTROL INTERNO".".pdf");
            }
            else
            {
                return "NO SE ENCONTRARON DATOS";
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }  
    }
    
    public function abrir_rep_control_abast(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('menu_rol') == 6)
        {
            $meses = DB::select("select mes,mes_descripcion,count(est_id) as tot_nroviajes, sum(cde_qabastecida) as tot_qabastecida from taller.vw_rep_ctrl_abastecimiento group by mes,mes_descripcion order by mes");
            if (count($meses) > 0) 
            {
                $view = \View::make('adblue.reportes.vw_control_abastecimiento',compact('meses'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a3','landscape');
                return $pdf->stream("CONTROL ABASTECIMIENTO".".pdf");
            }
            else
            {
                return "NO SE ENCONTRARON DATOS";
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }  
    }
    
    public function abrir_rep_control_abast_xplaca($est_id,$veh_id, Request $request)
    {
        if ($request->session()->has('id_usuario') && session('menu_rol') == 6)
        {
            $meses = DB::select("select distinct veh_id,est_descripcion,mes,TO_CHAR(cde_fecha,'YYYY') as anio,mes_descripcion from taller.vw_rep_ctrl_abast_irizar where est_id = $est_id and veh_id = $veh_id group by veh_id,est_descripcion,cde_fecha,mes,mes_descripcion order by est_descripcion asc");
            $totales = DB::table('taller.vw_consumos')->select(DB::raw('SUM(cde_qabastecida) as sum_qabastecida,count(veh_id) as count_vehid'))->where([['est_id',$est_id],['veh_id',$veh_id]])->get();
            
            $datos = DB::table('taller.vw_rep_ctrl_abast_irizar')->select(DB::raw('distinct rut_id,rut_descripcion'))->where([['est_id',$est_id],['veh_id',$veh_id]])->orderBy('rut_id','asc')->get();
            //dd($datos);
            $enteros='';
            foreach ($datos as $value_1){
                $enteros .=  $value_1->rut_descripcion.' INT,';
            }
            $var = trim($enteros,',');
            
            $numeric='';
            foreach ($datos as $value_2){
                $numeric .=  $value_2->rut_descripcion.' numeric,';
            }
            $var_1 = trim($numeric,',');
            
            $count = $datos->count();
            
            if (count($meses) > 0 && $totales->count() > 0) 
            {
                $view = \View::make('adblue.reportes.vw_control_abast_xplaca',compact('meses','totales','datos','var','var_1','count'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a3','landscape');
                return $pdf->stream("CONTROL IRIZAR".".pdf");
            }
            else
            {
                return "NO SE ENCONTRARON DATOS";
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }  
    }
    
//    public function abrir_rep_control_consumo($anio,$mes, Request $request)
//    {
//        if ($request->session()->has('id_usuario') && session('menu_rol') == 6)
//        {
//            $placas = DB::select("select xcde_placa,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(avg(xcde_ahxviaje),3) as ahorro, round(avg(xcde_exxviaje),3) as exceso,
//                                    (round(avg(xcde_ahxviaje),3) + round(avg(xcde_exxviaje),3)) as totalae from taller.control_consumo() 
//                                    where extract(month from xcde_fecha) = $mes and extract(year from xcde_fecha) = $anio group by xcde_placa order by xcde_placa asc");
//            $rutas = DB::select("select xcde_ruta,round(avg(xcde_consumo_real),3) as consumo,round(avg(xcde_kilometraje),3) as kg,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(avg(xcde_ahxviaje),3) as ahorro, round(avg(xcde_exxviaje),3) as exceso, 
//                                    (round(avg(xcde_ahxviaje),3) + round(avg(xcde_exxviaje),3)) as totalae from taller.control_consumo() 
//                                    where extract(month from xcde_fecha) = $mes and extract(year from xcde_fecha) = $anio group by xcde_ruta ORDER BY SUBSTRING(xcde_ruta FROM '([0-9]+)')::BIGINT ASC, xcde_ruta");
//            setlocale(LC_TIME, 'es');
//            $fecha = \DateTime::createFromFormat('!m', $mes);
//            $mes = strftime("%B", $fecha->getTimestamp());
//            if (count($placas) > 0 && count($rutas) > 0) 
//            {
//                $view = \View::make('adblue.reportes.vw_control_consumos',compact('placas','rutas','mes'))->render();
//                $pdf = \App::make('dompdf.wrapper');
//                $pdf->loadHTML($view)->setPaper('a4','landscape');
//                return $pdf->stream("CONTROL CONSUMO".".pdf");
//            }
//            else
//            {
//                return "NO SE ENCONTRARON DATOS";
//            }
//        }
//        else
//        {
//            return view('errors/vw_sin_acceso');
//        }  
//    }

}
