<?php

namespace App\Http\Controllers\programacion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblviajes_via;
use App\Http\Models\Tblvehiculos_veh;
use Carbon\Carbon;

class Viajes_Controller extends Controller
{   
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')],['ume_estado',1]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_viajes'],['btn_view',1]])->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('programacion/vw_viajes',compact('menu','permiso'));
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
            if ($request['show'] == 'datos') 
            {
                return $this->recuperar_codigo_vehiculo($id);
            } 
            if ($request['grid'] == 'viajes_vehiculos') 
            {
                return $this->generar_tabla_viajes_vehiculos($id, $request);
            }  
        }
        else
        {
            if($request['grid'] == 'viajes')
            {
                return $this->crear_tabla_viajes($request);
            }
            if($request['generar'] == 'cronograma')
            {
                return $this->generar_tabla_viajes($request);
            }
            if ($request['buscar'] == 'placa') 
            {
                return $this->recuperar_placa($id);
            }
            if($request['generar'] == 'limpiar_datos')
            {
                return $this->limpiar_datos();
            }
        }
    }
    
    public function crear_tabla_viajes(Request $request)
    {
        header('Content-type: application/json');
        $page = $request['page'];
        $limit = $request['rows'];
        $sidx = $request['sidx'];
        $sord = $request['sord'];
        $start = ($limit * $page) - $limit;
        if ($start < 0) {
            $start = 0;
        }
        
        $totalg = Tblviajes_via::Viajescount();
        $sql = DB::select("select * from cronograma.vw_programacion_viajes order by via_fecha asc, pro_id asc, via_id asc limit ".$limit." offset ".$start);
        
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
            $Lista->rows[$Index]['id'] = $Datos->via_id;          
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->via_id),
                trim($Datos->veh_placa),
                trim($Datos->pro_rutas),
                trim($Datos->via_fecha),
                trim($Datos->pro_horasalida),
                trim($Datos->pro_horallegada),
                $Datos->via_estado,
                trim($Datos->pro_estilo),
            );
        }
        return response()->json($Lista);
    }
    
    public function generar_tabla_viajes(Request $request)
    {
        /*$hoy = Carbon::now();
        $fin = $hoy->addMonths($request['cantidad']);
        $dias = Carbon::now()->startOfDay()->diffInDays($fin, false);  
        $meses = Carbon::now()->startOfDay()->diffInMonths($fin,false);*/
        
        for($i = 0; $i < $request['cantidad']; $i++)
        {
            $mes = $i + 1;
            $fin = Carbon::now()->addMonths($i+1);
            $dias = Carbon::now()->addMonths($i)->diffInDays($fin, false);
            //echo "mes: ".$mes." dias: ".$dias. "<br>";
            return $sql = DB::select("SELECT * FROM cronograma.fn_generar_cronograma_prueba(".$mes.",".$dias.")");
        }
        
    }

    public function recuperar_placa($veh_placa)
    {
        return Tblvehiculos_veh::where('veh_placa', 'like', '%'.strtoupper($veh_placa).'%')->get();
    }

    public function generar_tabla_viajes_vehiculos($veh_id, Request $request)
    {
        header('Content-type: application/json');
        $page = $request['page'];
        $limit = $request['rows'];
        $sidx = $request['sidx'];
        $sord = $request['sord'];
        $start = ($limit * $page) - $limit; 
        if ($start < 0) {
            $start = 0;
        }
        
        $totalg = Tblviajes_via::Viajescountbyid($veh_id);
        $sql = DB::select("select * from cronograma.vw_programacion_viajes where veh_id = ".$veh_id." order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);
        
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
            $Lista->rows[$Index]['id'] = $Datos->via_id."-".$Datos->via_fecha;          
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->via_id),
                trim($Datos->pro_rutas),
                trim($Datos->via_fecha),
                $Datos->via_estado,
                trim($Datos->pro_estilo),
            );
        }
        return response()->json($Lista);
    }
    
    public function limpiar_datos()
    {
        DB::select("delete from cronograma.tblviajes_via");
        DB::select("alter sequence cronograma.tblviajes_via_via_id_seq restart 1");
    }
    
}
