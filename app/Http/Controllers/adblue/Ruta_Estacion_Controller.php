<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblrutasestacion_rte;

class Ruta_Estacion_Controller extends Controller
{
    public function TblRutasestacion_Rte()
    {
        $Tblrutasestacion_rte = new Tblrutasestacion_rte;
        return $Tblrutasestacion_rte;
    }
    
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_ruta_estacion'],['btn_view',1]])->get();
            $estaciones = DB::table('taller.tblestaciones_est')->where('est_estado',1)->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('adblue/vw_ruta_estacion',compact('menu','estaciones','permiso'));
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
                return $this->recuperar_datos_rutaestacion($id, $request);
            }
            if ($request['show'] == 'estaciones') 
            {
                return $this->recuperar_datos_estaciones($id, $request);
            }
        }
        else
        {
            if($request['grid'] == 'rutas')
            {
                return $this->crear_tabla_rutas($request);
            }
            if($request['grid'] == 'ruta_estaciones')
            {
                return $this->crear_tabla_ruta_estaciones($request);
            }
            if($request['busqueda']=='estaciones')
            {
                return $this->autocompletar_estaciones($request);
            }
        }
    }
    
    public function autocompletar_estaciones(Request $request)
    {
        $Consulta = DB::table('taller.tblestaciones_est')->where('est_estado',1)->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->est_id;
            $Lista->label = strtoupper(trim($Datos->est_descripcion));
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }

    public function create(Request $request)
    {
        
    }

    public function edit($rte_id,Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->editar_rte_estacion($rte_id, $request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->modificar_rte_estado($rte_id, $request);
        }
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       if ($request['tipo'] == 1) 
        {
            return $this->crear_datos_ruta_estacion($request);
        }
    }
    
    public function recuperar_datos_rutaestacion($rut_id,Request $request)
    {
        $ruta_estacion = DB::table('taller.vw_rutas_estaciones')->where([['rte_estado',1],['rut_id',$rut_id]])->get();
        if ($ruta_estacion->count() > 0) 
        {
            return $ruta_estacion;
        }
        else
        {
            return 0;
        }
    }
    
    public function recuperar_datos_estaciones($rte_id,Request $request)
    {
        $estacion = DB::table('taller.vw_rutas_estaciones')->where('rte_id',$rte_id)->get();
        return $estacion;
    }
    
    public function crear_tabla_rutas(Request $request)
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

        $totalg = DB::table('taller.tblrutas_rut')->select(DB::raw('count(*) as total'))->where('rut_estado',1)->get();
        $sql = DB::table('taller.tblrutas_rut')->where('rut_estado',1)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
            $Lista->rows[$Index]['id'] = $Datos->rut_id;           
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->rut_id),
                trim($Datos->rut_descripcion),
                trim($Datos->rut_fecregistro)
            );
        }
        return response()->json($Lista);
    }
    
    public function crear_tabla_ruta_estaciones(Request $request)
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

        $totalg = DB::table('taller.vw_rutas_estaciones')->select(DB::raw('count(*) as total'))->where('rut_id',$request['rut_id'])->get();
        $sql = DB::table('taller.vw_rutas_estaciones')->where('rut_id',$request['rut_id'])->orderBy('rut_id', $sord)->limit($limit)->offset($start)->get();
        $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_ruta_estacion'],['btn_view',1]])->get();

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
            $Lista->rows[$Index]['id'] = $Datos->rte_id;
            if ($permiso[0]->btn_del == 1) 
            {
                if ($Datos->rte_estado == 1) {
                $nuevo = '<button class="btn btn-xl btn-success" onClick="cambiar_est_rte('.trim($Datos->rte_id).','.$Datos->rut_id.',0)" type="button"><i class="fa fa-check"></i> ACTIVO</button>';
                }else{
                    $nuevo = '<button class="btn btn-xl btn-danger" onClick="cambiar_est_rte('.trim($Datos->rte_id).','.$Datos->rut_id.',1)" type="button"><i class="fa fa-times"></i> INACTIVO</button>'; 
                }
            }
            else
            {
                if ($Datos->rte_estado == 1) {
                    $nuevo = '<button class="btn btn-xl btn-success" type="button" onclick="sin_permiso()"><i class="fa fa-check"></i> ACTIVO</button>';
                }else{
                    $nuevo = '<button class="btn btn-xl btn-danger" type="button" onclick="sin_permiso()"><i class="fa fa-times"></i> INACTIVO</button>'; 
                }
            }            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->rte_id),
                trim($Datos->est_descripcion),
                trim($Datos->rte_consumo),
                trim($Datos->rte_fecregistro),
                trim($Datos->rte_anio),
                $nuevo
            );
        }
        return response()->json($Lista);
    }
    
    public function crear_datos_ruta_estacion(Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $filas = count($request['nro_filas']);
                for($i=0; $i<$filas; $i++)
                {
                    $this->TblRutasestacion_Rte()->insert([
                        'rut_id' => $request['id_ruta'],
                        'est_id' => $request['estaciones'][$i],
                        'rte_consumo' => $request['consumo'][$i],
                        'rte_fecregistro' => date('Y-m-d'),
                        'rte_anio' => date('Y'),
                    ]);
                }
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
    
    public function editar_rte_estacion($rte_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $this->TblRutasestacion_Rte()->recuperar($rte_id)->update([
                    'est_id' => $request['estacion'],
                    'rte_consumo' => isset($request['consumo']) ? $request['consumo'] : 0,
                    'rte_usumodificacion' => session('id_usuario'),
                    'rte_fecmodificacion' => date('Y-m-d'),
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
    
    public function modificar_rte_estado($rte_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $this->TblRutasestacion_Rte()->recuperar($rte_id)->update([
                    'rte_estado' => $request['estado'],
                    'rte_usumodificacion' => session('id_usuario'),
                    'rte_fecmodificacion' => date('Y-m-d'),
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
