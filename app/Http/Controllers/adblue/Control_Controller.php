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
            $menu_dashboard = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',2]])->orderBy('menu_id','asc')->get();
            return view('adblue/vw_control',compact('menu_registro','menu_dashboard'));
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
        $function = DB::select("select taller.control_salida(1,".round($request['cantidad'],3).")");
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
        $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
        if ($start < 0) {
            $start = 0;
        }

        //$totalg = DB::table('taller.tblcontrol_con')->select(DB::raw('count(*) as total'))->get();
        //$sql = DB::table('taller.tblcontrol_con')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $totalg = DB::select("select count(*) as total from taller.fn_control_total_salida()");
        $sql = DB::select("select * from taller.fn_control_total_salida() order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);

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
                trim($Datos->xcon_fecregistro),
                trim($Datos->xcon_ingreso),
                trim($Datos->xcon_totsalida),
                trim($Datos->xcon_stop),
                trim($Datos->xcon_cantidad)
            );
        }
        return response()->json($Lista);
    }
    
    public function abrir_rep_control_diario(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('menu_rol') == 6)
        {
            //$control = DB::table('taller.tblcontrol_con')->orderBy('con_id','asc')->get();
            $meses = DB::select("select * from taller.vw_rep_ctrl_diario_adblue order by mes asc");
            if(count($meses) > 0)
            {
                $view = \View::make('adblue.reportes.vw_control_interno',compact('meses'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
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
            $totales = DB::table('taller.vw_consumos')->select(DB::raw('SUM(cde_qabastecida) as sum_qabastecida,count(veh_id) count_vehid'))->where([['est_id',$est_id],['veh_id',$veh_id]])->get();
            if (count($meses) > 0 && $totales->count() > 0) 
            {
                $view = \View::make('adblue.reportes.vw_control_abast_xplaca',compact('meses','totales'))->render();
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

}
