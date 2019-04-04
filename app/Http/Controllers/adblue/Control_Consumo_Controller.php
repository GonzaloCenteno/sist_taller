<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblconsumodetalle_cde;

class Control_Consumo_Controller extends Controller
{
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu_registro = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',1]])->orderBy('menu_id','asc')->get();
            $menu_dashboard = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',2]])->orderBy('menu_id','asc')->get();
            return view('adblue/vw_control_consumo',compact('menu_registro','menu_dashboard'));
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
            if ($request['show'] == 'consumo') 
            {
                return $this->traer_datos_consumos($id, $request);
            }
            if ($request['show'] == 'consumodetalle')
            {
                return $this->traer_datos_detconsumo($id, $request);
            }
        }
        else
        {
            if($request['grid'] == 'control_consumo')
            {
                return $this->crear_tabla_control_consumo($request);
            }
            if($request['grid'] == 'estaciones_consumo')
            {
                return $this->crear_tabla_estaciones_consumo($request);
            }
        }
    }

    public function create(Request $request)
    {
        
    }

    public function edit($cde_id,Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->editar_estacion_consumo($cde_id, $request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->editar_consumo_detalle($cde_id, $request);
        }
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }
    
    public function crear_tabla_control_consumo(Request $request)
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
        
        if (isset($request['veh_placa'])) 
        {
            $totalg = DB::select("select count(*) as total from taller.control_consumo() where EXTRACT(MONTH FROM xcde_fecha) = ". $request['mes'] ." and EXTRACT(YEAR FROM xcde_fecha)= ". $request['anio'] ." and xcde_placa like '%". $request['veh_placa'] ."%' ");
            $sql = DB::select("select * from taller.control_consumo() where EXTRACT(MONTH FROM xcde_fecha) = ". $request['mes'] ." and EXTRACT(YEAR FROM xcde_fecha)= ". $request['anio'] ." and xcde_placa like '%". $request['veh_placa'] ."%' order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);
        }
        else
        {
            $totalg = DB::select("select count(*) as total from taller.control_consumo() where EXTRACT(MONTH FROM xcde_fecha) = ". $request['mes'] ." and EXTRACT(YEAR FROM xcde_fecha)= ". $request['anio'] ." ");
            $sql = DB::select("select * from taller.control_consumo() where EXTRACT(MONTH FROM xcde_fecha) = ". $request['mes'] ." and EXTRACT(YEAR FROM xcde_fecha)= ". $request['anio'] ." order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);
        }

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
            $Lista->rows[$Index]['id'] = $Datos->xcca_id;           
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->xcca_id),
                trim($Datos->xcde_id),
                trim($Datos->xcde_fecha),
                trim($Datos->xcde_placa),
                trim($Datos->xcde_conductor),
                trim($Datos->xcde_copiloto),
                trim($Datos->xcde_ruta),
                trim($Datos->xcde_qabastecida),
                trim($Datos->xcde_consumo_real),
                trim($Datos->xcde_consumo_deseado),
                trim($Datos->xcde_ahorro_exceso_com),
                trim($Datos->xcde_montoptimo),
                trim($Datos->xcde_ahex_gral),
                trim($Datos->xcde_ahxviaje),
                trim($Datos->xcde_exxviaje),
                trim($Datos->xcde_kmi),
                trim($Datos->xcde_kmf),
                trim($Datos->xcde_kilometraje),
                trim($Datos->xcde_rendimiento),
            );
        }
        return response()->json($Lista);
    }
    
    public function crear_tabla_estaciones_consumo(Request $request)
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

        $totalg = DB::table('taller.vw_consumos')->select(DB::raw('count(*) as total'))->where('cca_id',$request['cca_id'])->get();
        $sql = DB::table('taller.vw_consumos')->select('cde_id','est_descripcion','cde_qabastecida','rut_descripcion')->where('cca_id',$request['cca_id'])->orderBy('cde_id', $sord)->limit($limit)->offset($start)->get();
        
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
        $Lista->consumos = $sql;
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->cde_id;          
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->cde_id),
                trim($Datos->est_descripcion),
                trim($Datos->cde_qabastecida)
            );
        }
        return response()->json($Lista);
    }
    
    public function traer_datos_consumos($cde_id, Request $request)
    {
        $datos = DB::table('taller.vw_consumos')->select('cde_id','est_descripcion','cde_qabastecida')->where('cde_id',$cde_id)->get();
        return $datos;
    }
    
    public function traer_datos_detconsumo($cde_id, Request $request)
    {
        $detalle = DB::table('taller.vw_consumos')->select('cde_id','cde_condeseado','cde_montoptimo','veh_placa')->where('cde_id',$cde_id)->get();
        return $detalle;
    }
    
    public function editar_estacion_consumo($cde_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                DB::table('taller.tblconsumodetalle_cde')->where('cde_id',$cde_id)->update([
                    'cde_qabastecida' => round(trim($request['qabastecida']),3),
                    'cde_usumodificacion' => session('id_usuario'),
                    'cde_fecmodificacion' => date('Y-m-d H:i:s')
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
    
    public function editar_consumo_detalle($cde_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                DB::table('taller.tblconsumodetalle_cde')->where('cde_id',$cde_id)->update([
                    'cde_condeseado' => round(trim($request['cde_condeseado']),3),
                    'cde_montoptimo' => round(trim($request['cde_montoptimo']),3),
                    'cde_usumodificacion' => session('id_usuario'),
                    'cde_fecmodificacion' => date('Y-m-d H:i:s')
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
    
}
