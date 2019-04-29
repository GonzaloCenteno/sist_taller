<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblruta_rut;

class Rutas_Controller extends Controller
{
    public function TblRutas_Rut()
    {
        $Tblruta_rut = new Tblruta_rut;
        return $Tblruta_rut;
    }
    
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu_registro = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',1]])->orderBy('menu_id','asc')->get();
            
            return view('adblue/vw_rutas',compact('menu_registro'));
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
                return $this->recuperar_datos_rutas($id);
            }
        }
        else
        {
            if($request['grid'] == 'rutas')
            {
                return $this->crear_tabla_rutas($request);
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
                $validar = $this->TblRutas_Rut()->where('rut_descripcion',strtoupper($request['descripcion']))->get();
                if ($validar->count() > 0) 
                {
                    $success = 0;
                }
                else
                {
                    $this->TblRutas_Rut()->insert([
                        'rut_descripcion' => strtoupper(trim($request['descripcion'])),
                        'rut_fecregistro' => date('d-m-Y'),
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

    public function edit($rut_id,Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->editar_registro_ruta($rut_id,$request);
        }
        if($request['tipo'] == 2)
        {
            return $this->modificar_estado($rut_id,$request);
        }
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }
    public function recuperar_datos_rutas($rut_id)
    {
        $rutas = $this->TblRutas_Rut()->recuperar($rut_id)->get();
        return $rutas;
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

        $totalg = $this->TblRutas_Rut()->tblrutacount()->get();
        $sql = $this->TblRutas_Rut()->paginacion($sidx,$sord,$limit,$start)->get();

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
            if ($Datos->rut_estado == 1) {
                $nuevo = '<button class="btn btn-xl btn-success" type="button" onclick="cambiar_estado_ruta('.trim($Datos->rut_id).',0)"><i class="fa fa-check"></i> ACTIVO</button>';
            }else{
                $nuevo = '<button class="btn btn-xl btn-danger" type="button" onclick="cambiar_estado_ruta('.trim($Datos->rut_id).',1)"><i class="fa fa-times"></i> INACTIVO</button>'; 
            }            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->rut_id),
                trim($Datos->rut_descripcion),
                trim($Datos->rut_fecregistro),
                $nuevo
            );
        }
        return response()->json($Lista);
    }
    
    public function editar_registro_ruta($rut_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $validar = $this->TblRutas_Rut()->where('rut_descripcion',strtoupper($request['descripcion']))->where('rut_id','<>',$rut_id)->get();
                if ($validar->count() > 0) 
                {
                    $success = 0;
                }
                else
                {
                    $this->TblRutas_Rut()->recuperar($rut_id)->update([
                        'rut_descripcion' => strtoupper(trim($request['descripcion'])),
                        'rut_fecmodificacion' => date('d-m-Y'),
                        'rut_usumodificacion' => session('id_usuario'),
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
    
    public function modificar_estado($rut_id, Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $this->TblRutas_Rut()->recuperar($rut_id)->update([
                    'rut_estado' => $request['estado'],
                ]);
                DB::table('taller.tblrutasestacion_rte')->where('rut_id',$rut_id)->update([
                        'rte_estado' => $request['estado'],
                        'rte_usumodificacion' => session('id_usuario'),
                        'rte_fecmodificacion' => date('d-m-Y'),
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
