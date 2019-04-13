<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblcapacidad_cap;

class Capacidad_Controller extends Controller
{
    public function TblCapacidad_Cap()
    {
        $Tblcapacidad_cap = new Tblcapacidad_cap;
        return $Tblcapacidad_cap;
    }
    
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu_registro = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',1]])->orderBy('menu_id','asc')->get();
            $menu_dashboard = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',2]])->orderBy('menu_id','asc')->get();
            
            return view('adblue/vw_capacidad',compact('menu_registro','menu_dashboard'));
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
                return $this->recuperar_datos_capacidad($id);
            }
        }
        else
        {
            if($request['grid'] == 'capacidad')
            {
                return $this->crear_tabla_capacidad($request);
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
                
                $this->TblCapacidad_Cap()->insert([
                    'cap_val' => trim($request['valor']),
                    'cap_fecregistro' => date('d-m-Y'),
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

    public function edit($cap_id,Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->editar_registro_capacidad($cap_id,$request);
        }
        if($request['tipo'] == 2)
        {
            return $this->modificar_estado($cap_id,$request);
        }
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }
    public function recuperar_datos_capacidad($cap_id)
    {
        $capacidad = $this->TblCapacidad_Cap()->recuperar($cap_id)->get();
        return $capacidad;
    }
    
    public function crear_tabla_capacidad(Request $request)
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

        $totalg = $this->TblCapacidad_Cap()->tblcapacidadcount()->get();
        $sql = $this->TblCapacidad_Cap()->paginacion($sidx,$sord,$limit,$start)->get();

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
            $Lista->rows[$Index]['id'] = $Datos->cap_id;
            if ($Datos->cap_estado == 1) {
                $nuevo = '<button class="btn btn-xl btn-success" type="button" onclick="cambiar_estado_capacidad('.trim($Datos->cap_id).',0)"><i class="fa fa-check"></i> ACTIVO</button>';
            }else{
                $nuevo = '<button class="btn btn-xl btn-danger" type="button" onclick="cambiar_estado_capacidad('.trim($Datos->cap_id).',1)"><i class="fa fa-times"></i> INACTIVO</button>'; 
            }            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->cap_id),
                trim($Datos->cap_val),
                trim($Datos->cap_fecregistro),
                $nuevo
            );
        }
        return response()->json($Lista);
    }
    
    public function editar_registro_capacidad($cap_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $this->TblCapacidad_Cap()->recuperar($cap_id)->update([
                    'cap_val' => trim($request['valor']),
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
    
    public function modificar_estado($cap_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $this->TblCapacidad_Cap()->recuperar($cap_id)->update([
                    'cap_estado' => $request['estado'],
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
