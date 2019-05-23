<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblcostoadblue_coa;
use Carbon\Carbon;

class Costo_Adblue_Controller extends Controller
{
    public function TblCostoAdblue_Coa()
    {
        $Tblcostoadblue_coa = new Tblcostoadblue_coa;
        return $Tblcostoadblue_coa;
    }
    
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_costo_adblue'],['btn_view',1]])->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('adblue/vw_costos_adblue',compact('menu','permiso'));
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
                return $this->recuperar_datos_costos_adblue($id);
            }
        }
        else
        {
            if($request['grid'] == 'costos_adblue')
            {
                return $this->crear_tabla_costos_adblue($request);
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
                $validar = $this->TblCostoAdblue_Coa()->where([['coa_anio',$request['anio']],['coa_mes',$request['mes']]])->get();
                if ($validar->count() > 0) 
                {
                    $success = 0;
                }
                else
                {
                    $this->TblCostoAdblue_Coa()->insert([
                        'coa_anio' => $request['anio'],
                        'coa_mes' => $request['mes'],
                        'coa_saldo' => round($request['costo'],3),
                        'coa_usucreacion' => session('id_usuario'),
                        'coa_fecregistro' => date('Y-m-d H:i:s'),
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

    public function edit($coa_id,Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $validar = $this->TblCostoAdblue_Coa()->where([['coa_anio',$request['anio']],['coa_mes',$request['mes']],['coa_id','<>',$coa_id]])->get();
                if ($validar->count() > 0) 
                {
                    $success = 0;
                }
                else
                {
                    $this->TblCostoAdblue_Coa()->recuperar($coa_id)->update([
                        'coa_saldo' => round($request['costo'],3),
                        'coa_usumodificacion' => session('id_usuario'),
                        'coa_fecmodificacion' => date('Y-m-d H:i:s'),
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

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }
    public function recuperar_datos_costos_adblue($coa_id)
    {
        $costos = $this->TblCostoAdblue_Coa()->recuperar($coa_id)->get();
        return $costos;
    }
    
    public function crear_tabla_costos_adblue(Request $request)
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
        
        if(isset($request['anio']))
        {
            $totalg = $this->TblCostoAdblue_Coa()->where('coa_anio',$request['anio'])->tblcostoadbluecount()->get();
            $sql = $this->TblCostoAdblue_Coa()->where('coa_anio',$request['anio'])->paginacion($sidx,$sord,$limit,$start)->get();
        }
        else
        {
            $totalg = $this->TblCostoAdblue_Coa()->tblcostoadbluecount()->get();
            $sql = $this->TblCostoAdblue_Coa()->paginacion($sidx,$sord,$limit,$start)->get();
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
            $Lista->rows[$Index]['id'] = $Datos->coa_id;
            setlocale(LC_TIME, 'es');
            $fecha = \DateTime::createFromFormat('!m', $Datos->coa_mes);
            $mes = strftime("%B", $fecha->getTimestamp());
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->coa_id),
                trim($Datos->coa_anio),
                strtoupper($mes),
                trim($Datos->coa_saldo),
                Carbon::parse($Datos->coa_fecregistro)->format('d/m/Y')
            );
        }
        return response()->json($Lista);
    }

}
