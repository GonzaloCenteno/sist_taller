<?php

namespace App\Http\Controllers\grifo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblvalecabecera_vca;

class Grifo_Vales_Controller extends Controller
{   
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')],['ume_estado',1]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_vales_grifo'],['btn_view',1]])->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('grifo/vw_grifo_vales',compact('menu','permiso'));
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
            if($request['grid'] == 'vales')
            {
                return $this->crear_tabla_vales($request);
            }
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            DB::beginTransaction();
            try{
                
                Tblvalecabecera_vca::where('vca_id',$request['vca_id'])->update([
                    'vca_estado' => 0,
                    'vca_usumodificacion' => session('id_usuario'),
                    'vca_fecmodificacion' => date('Y-m-d H:i:s')
                ]);
                DB::commit();
                return 1;
                
            } catch (\Exception $ex) {
                DB::rollback();
                return $ex->getMessage();
            }
        }
    }
    
    public function crear_tabla_vales(Request $request)
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
        
        if ($request['indice'] == '0') 
        {
            $totalg = DB::select("select count(*) as total from grifo.vw_vales");
            $sql = DB::select("select * from grifo.vw_vales order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);
        }
        else
        {
            if(isset($request["vale"]))
            {
                $vale = trim($request['vale']);
            }
            else
            {
                $vale = "";
            }
            if(isset($request["tripulante"]))
            {
                $tripulante = strtoupper(trim($request['tripulante']));
            }    
            else
            {
                $tripulante = "";
            }
            if(isset($request["placa"]))
            {
                $placa = strtoupper(trim($request['placa']));
            }    
            else
            {
                $placa = "";
            }
            if(isset($request["fecinicio"]) && isset($request["fecfin"]))
            {
                $fdesde = $request['fecinicio'];
                $fhasta = $request['fecfin'];
            }    
            else
            {
                $fdesde = "";
                $fhasta = "";
            }
            
            $where="WHERE 1=1";
            if($vale!='')
            {
                $where.= " AND vca_numvale = '$vale'";
            }
            
            if($tripulante!='')
            {
                $where.= " AND tripulante LIKE '%$tripulante%'";
            }
                    
            if($placa!='')
            {
                $where.= " AND veh_placa LIKE '%$placa%'";
            }
            
            if($fdesde!='' && $fhasta!='')
            {
                $where.= " AND vca_fecha between '$fdesde'  and '$fhasta'";
            }
            
            $totalg = DB::select("select count(*) as total from grifo.vw_vales ".$where);
            $sql = DB::select("select * from grifo.vw_vales ".$where." order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);
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
            $Lista->rows[$Index]['id'] = $Datos->vca_id;       
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->vca_id),
                trim($Datos->vca_estado),
                trim($Datos->vca_numvale),
                trim($Datos->veh_placa),
                trim($Datos->tri_nrodoc),
                trim($Datos->tripulante),
                trim(\Carbon\Carbon::parse($Datos->vca_fecha)->format('d/m/Y')),
                trim($Datos->vca_hora),
                trim($Datos->est_descripcion),
                trim($Datos->vca_kilometraje),
                trim($Datos->vca_cntmtri),
                trim($Datos->vca_cntmtrf),
                trim($Datos->bom_descripcion),
                trim($Datos->cec_codigo),
                trim($Datos->pro_codigo),
                trim($Datos->pro_descripcion),
                trim($Datos->pro_unidad),
                trim($Datos->vde_cantidad)
            );
        }
        return response()->json($Lista);
    }
}
