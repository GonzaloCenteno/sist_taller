<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblvehiculos_veh;

class Vehiculos_Controller extends Controller
{   
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')],['ume_estado',1]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_vehiculos'],['btn_view',1]])->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('adblue/vw_vehiculos',compact('menu','permiso'));
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
        }
        else
        {
            if($request['grid'] == 'vehiculos')
            {
                return $this->crear_tabla_vehiculos($request);
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
                $validar = Tblvehiculos_veh::where('veh_placa',strtoupper(trim($request['placa'])))->get();
                if ($validar->count() > 0) 
                {
                    DB::rollback();
                    return 0;
                }
                else
                {
                    Tblvehiculos_veh::insert([
                        'veh_placa' => strtoupper(trim($request['placa'])),
                        'veh_marca' => strtoupper(trim($request['marca'])),
                        'veh_clase' => strtoupper(trim($request['clase'])),
                        'veh_carroceria' => strtoupper(trim($request['carroceria'])),
                        'mod_nombre' => strtoupper(trim($request['modelo'])),
                        'cat_nombre' => strtoupper(trim($request['categoria'])),
                        'veh_codigo' => strtoupper(trim($request['codigo'])),
                        'veh_referencia' => 'CROMOTALLER',
                    ]);
                    DB::commit();
                    return 1;
                }
            } catch (\Exception $ex) {
                DB::rollback();
                return $ex->getMessage();                
            }
        }
    }

    public function edit($veh_id,Request $request)
    {
        if($request['tipo'] == 1)
        {
            return $this->editar_vehiculo($veh_id, $request);
        }
        if($request['tipo'] == 2)
        {
            return $this->editar_estado_programacion($veh_id, $request);
        }
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }
    
    public function recuperar_codigo_vehiculo($veh_id)
    { 
        return Tblvehiculos_veh::Recuperar($veh_id)->first();
    }
    
    public function crear_tabla_vehiculos(Request $request)
    {
        header('Content-type: application/json');
        $page = $request['page'];
        $limit = $request['rows'];
        $sidx = $request['sidx'];
        $sord = $request['sord'];
        $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
        if ($start < 0) {
            $start = 0;
        }
        
        if ($request['busqueda'] == '0') 
        {
            $totalg = Tblvehiculos_veh::Tblvehiculocount();
            $sql = Tblvehiculos_veh::Paginacion($sidx,$sord,$limit,$start);
        }
        else
        {
            $totalg = Tblvehiculos_veh::TblvehiculoLikecount($request['busqueda']);
            $sql = Tblvehiculos_veh::PaginacionLike($sidx,$sord,$limit,$start,$request['busqueda']);
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
            $Lista->rows[$Index]['id'] = $Datos->veh_id;          
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->veh_id),
                trim($Datos->veh_placa),
                trim($Datos->veh_codigo),
                trim($Datos->veh_marca),
                trim($Datos->veh_clase) .' / '.trim($Datos->veh_carroceria) .' / '. trim($Datos->mod_nombre) . ' / '.  trim($Datos->cat_nombre),
                trim($Datos->veh_referencia),
                $Datos->veh_programacion
            );
        }
        return response()->json($Lista);
    }
    
    public function editar_vehiculo($veh_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $validar = Tblvehiculos_veh::UniqueCodigo($request['codigo'],$veh_id);
                
                if ($validar->count() > 0) 
                {
                    DB::rollback();
                    return 0;
                }
                else
                {
                    Tblvehiculos_veh::Recuperar($veh_id)->update([
                        'veh_codigo' => strtoupper(trim($request['codigo']))
                    ]);
                    DB::commit();
                    return 1;
                }
            } catch (\Exception $ex) {
                DB::rollback();
                return $ex->getMessage();
            }
        }
    }
    
    public function editar_estado_programacion($veh_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{

                Tblvehiculos_veh::Recuperar($veh_id)->update([
                    'veh_programacion' => $request['estado']
                ]);
                DB::commit();
                return 1;
                
            } catch (\Exception $ex) {
                DB::rollback();
                return $ex->getMessage();
            }
        }
    }
    
}
