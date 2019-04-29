<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblestacion_est;

class Estaciones_Controller extends Controller
{
    public function TblEstaciones_Est()
    {
        $Tblestacion_est = new Tblestacion_est;
        return $Tblestacion_est;
    }
    
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu_registro = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',1]])->orderBy('menu_id','asc')->get();
            return view('adblue/vw_estaciones',compact('menu_registro'));
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
                return $this->recuperar_datos_estaciones($id);
            }
        }
        else
        {
            if($request['grid'] == 'estaciones')
            {
                return $this->crear_tabla_estaciones($request);
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
                $validar = $this->TblEstaciones_Est()->where('est_descripcion',strtoupper($request['descripcion']))->get();
                if ($validar->count() > 0) 
                {
                    $success = 0;
                }
                else
                {
                    $this->TblEstaciones_Est()->insert([
                        'est_descripcion' => strtoupper(trim($request['descripcion'])),
                        'est_fecregistro' => date('d-m-Y'),
                    ]);
                    $success = 1;
                }
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
        else if($success == 0)
        {
            return $success;
        }
        else
        {
            return $error;
        } 
    }

    public function edit($est_id,Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->editar_registro_estacion($est_id,$request);
        }
        if($request['tipo'] == 2)
        {
            return $this->modificar_estado($est_id,$request);
        }
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }
    
    public function recuperar_datos_estaciones($est_id)
    {
        $estaciones = $this->TblEstaciones_Est()->recuperar($est_id)->get();
        return $estaciones;
    }
    
    public function crear_tabla_estaciones(Request $request)
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

        $totalg = $this->TblEstaciones_Est()->tblestacioncount()->get();
        $sql = $this->TblEstaciones_Est()->paginacion($sidx,$sord,$limit,$start)->get();

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
            $Lista->rows[$Index]['id'] = $Datos->est_id;
            if ($Datos->est_estado == 1) {
                $nuevo = '<button class="btn btn-xl btn-success" type="button" onclick="cambiar_estado_estacion('.trim($Datos->est_id).',0)"><i class="fa fa-check"></i> ACTIVO</button>';
            }else{
                $nuevo = '<button class="btn btn-xl btn-danger" type="button" onclick="cambiar_estado_estacion('.trim($Datos->est_id).',1)"><i class="fa fa-times"></i> INACTIVO</button>'; 
            }            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->est_id),
                trim($Datos->est_descripcion),
                trim($Datos->est_fecregistro),
                $nuevo
            );
        }
        return response()->json($Lista);
    }
    
    public function editar_registro_estacion($est_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $validar = $this->TblEstaciones_Est()->where('est_descripcion',strtoupper($request['descripcion']))->where('est_id','<>',$est_id)->get();
                if ($validar->count() > 0) 
                {
                    $success = 0;
                }
                else
                {
                    $this->TblEstaciones_Est()->recuperar($est_id)->update([
                        'est_descripcion' => strtoupper(trim($request['descripcion'])),
                        'est_fecmodificacion' => date('d-m-Y'),
                        'est_usumodificacion' => session('id_usuario'),
                    ]);
                    $success = 1;
                }
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
        else if($success == 0)
        {
            return $success;
        }
        else
        {
            return $error;
        }  
    }
    
    public function modificar_estado($est_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $this->TblEstaciones_Est()->recuperar($est_id)->update([
                    'est_estado' => $request['estado'],
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
