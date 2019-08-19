<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Http\Models\Tblcontrol_con;

class Control_Controller extends Controller
{
    public function accesos()
    {
        $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_control'],['btn_view',1]])->get();
        $rol = DB::table('permisos.tblsistemasrol_sro')->where([['sro_id',$permiso[0]->sro_id],['sro_descripcion', 'like', '%ADMINISTRADOR%']])->get();
        if ($rol->count() > 0) 
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')],['ume_estado',1]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_control'],['btn_view',1]])->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('adblue/vw_control',compact('menu','permiso'));
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
            if($request['grid'] == 'detalle_control')
            {
                return $this->crear_tabla_detalle_control($request);
            }
        }
    }

    public function create(Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                
                Tblcontrol_con::insert([
                    'est_id' => 1,
                    'con_fecregistro' => date('d-m-Y H:i:s'),
                    'con_usuregistro' => session('id_usuario'),
                    'con_cantidad' => trim($request['cantidad']),
                    'con_observacion' => trim(strtoupper($request['observacion'])),
                ]);
                $success = 1;
                
                DB::commit();
            } catch (\Exception $ex) {
                $success = 2;
                $error = $ex->getMessage();
                DB::rollback();
            }
        }

        if ($success == 1) 
        {
            return $success;
        }
        else
        {
            return $error;
        } 
    }

    public function edit($con_id,Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                Tblcontrol_con::where('con_id',$con_id)->update([
                    'con_fecregistro' => trim($request['con_fecregistro']).' '.date('H:i:s'),
                    'con_usumodificacion' => session('id_usuario'),
                    'con_fecmodificacion' => date('Y-m-d H:i:s')
                ]);
                $success = 1;
                DB::commit();
            } catch (\Exception $ex) {
                $success = 2;
                $error = $ex->getMessage();
                DB::rollback();
            }
        }

        if ($success == 1) 
        {
            return $success;
        }
        else
        {
            return $error;
        }
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
    
    public function crear_tabla_detalle_control(Request $request)
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
        
        $consulta = DB::select("select * from taller.fn_control_diario_adblue() where xcon_id = ".$request['con_id']);
        $totalg = DB::select("select count(*) as total from taller.vw_control_consumo where cde_fecha >= '".$consulta[0]->xcon_fecinicio."' and cde_fecha < '".$consulta[0]->xcon_fecfin."' ");
        $sql = DB::select("select * from taller.vw_control_consumo where cde_fecha >= '".$consulta[0]->xcon_fecinicio."' and cde_fecha < '".$consulta[0]->xcon_fecfin."' order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);

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
            $Lista->rows[$Index]['id'] = $Datos->cde_id;           
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->cde_id),
                trim(\Carbon\Carbon::parse($Datos->cde_fecha)->format('d/m/Y')),
                trim($Datos->est_descripcion),
                trim($Datos->veh_placa),
                trim($Datos->cde_qabastecida),
            );
        }
        return response()->json($Lista);
    }
    
    public function abrir_rep_control_diario(Request $request)
    {
        if ($request->session()->has('id_usuario') && $this->accesos() == 1)
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
            return view('errors/vw_sin_permiso');
        }  
    }
    
    public function abrir_rep_control_abast(Request $request)
    {
        if ($request->session()->has('id_usuario') && $this->accesos() == 1)
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
            return view('errors/vw_sin_permiso');
        }  
    }
    
    public function abrir_rep_control_abast_xplaca($est_id,$veh_id, Request $request)
    {
        if ($request->session()->has('id_usuario') && $this->accesos() == 1)
        {
            $meses = DB::select("select distinct est_id,veh_id,est_descripcion,mes,anio,mes_descripcion from taller.vw_rep_ctrl_abast_irizar where est_id = $est_id and veh_id = $veh_id group by est_id,veh_id,est_descripcion,anio,mes,mes_descripcion order by est_descripcion asc");
            $totales = DB::table('taller.vw_consumos')->select(DB::raw('SUM(cde_qabastecida) as sum_qabastecida,count(veh_id) as count_vehid'))->where([['est_id',$est_id],['veh_id',$veh_id]])->get();
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
            return view('errors/vw_sin_permiso');
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
