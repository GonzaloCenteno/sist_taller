<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblconsumodetalle_cde;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportesExport;

class Control_Consumo_Controller extends Controller
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
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_control_consumo'],['btn_view',1]])->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('adblue/vw_control_consumo',compact('menu','permiso'));
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
            if($request['grid'] == 'prom_gen_rut')
            {
                return $this->crear_tabla_promedios_generales($request);
            }
            if($request['grid'] == 'dat_gen_escania')
            {
                return $this->crear_tabla_datos_generales_scania($request);
            }
            if($request['grid'] == 'dat_gen_irizar')
            {
                return $this->crear_tabla_datos_generales_irizar($request);
            }
            if($request['datos'] == 'traer_saldo')
            {
                return $this->traer_datos_saldo($request);
            }
            if($request['grid'] == 'cost_opt_ruta')
            {
                return $this->crear_tabla_costo_optimo_ruta($request);
            }
            if($request['grid'] == 'cost_gen_abast_ruta')
            {
                return $this->crear_tabla_costo_gen_abast_ruta($request);
            }
            if($request['grid'] == 'cost_gen_abast_placa')
            {
                return $this->crear_tabla_costo_gen_abast_placa($request);
            }
            if($request['reportes'] == 'rep_generales')
            {
                return $this->reportes_generales($request);
            }
            if($request['reportes'] == 'reporte_informacion_excel')
            {
                return $this->reportes_informacion_excel($request);
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
                trim($Datos->xcde_rendimiento_lt),
                trim($Datos->xcde_rendimiento_gl),
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
        $datos = DB::table('taller.vw_consumos')->select('cde_id','est_descripcion','cde_qabastecida','cde_qparcial')->where('cde_id',$cde_id)->get();
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
    
    public function crear_tabla_promedios_generales(Request $request)
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
            
        $totalg = DB::select("select count(*) as total from taller.control_consumo() 
                                where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." ");
        $sql = DB::select("select xcde_ruta,round(avg(xcde_consumo_real),3) as consumo,round(avg(xcde_kilometraje),3) as kg,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(sum(xcde_ahxviaje),3) as ahorro, round(sum(xcde_exxviaje),3) as exceso, 
                                (round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as totalae,count(xcde_ruta) as nro_viajes from taller.control_consumo() 
                                where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." group by xcde_ruta ORDER BY SUBSTRING(".$sidx." FROM '([0-9]+)')::BIGINT ".$sord.", ".$sidx." limit ".$limit." offset ".$start);

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
        
        $array = array();
        $sum = DB::select("select count(xcde_ruta) as sum from taller.control_consumo() where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." ");
        $array['sum'] = $sum[0]->sum;
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $Lista->userdata = $array;
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->xcde_ruta;           
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->xcde_ruta),
                trim($Datos->consumo),
                trim($Datos->kg),
                trim($Datos->rendimiento),
                trim($Datos->ahorro),
                trim($Datos->exceso),
                trim($Datos->totalae),
                trim($Datos->nro_viajes)
            );
        }
        return response()->json($Lista);
    }
    
    public function crear_tabla_datos_generales_scania(Request $request)
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

        $totalg = DB::select("select count(*) as total from taller.control_consumo() 
                                where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." and xveh_marca like '%SCANIA%'");
        $sql = DB::select("select xcde_placa,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(sum(xcde_ahxviaje),3) as ahorro, round(sum(xcde_exxviaje),3) as exceso,
                                   (round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as totalae,count(xcde_placa) as nro_viajes from taller.control_consumo() 
                                    where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." and xveh_marca like '%SCANIA%' group by xcde_placa order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);

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
        
        $array = array();
        $sum = DB::select("select count(xcde_placa) as sum from taller.control_consumo() where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." and xveh_marca like '%SCANIA%' ");
        $array['sum'] = $sum[0]->sum;
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $Lista->userdata = $array;
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->xcde_placa;           
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->xcde_placa),
                trim($Datos->rendimiento),
                trim($Datos->ahorro),
                trim($Datos->exceso),
                trim($Datos->totalae),
                trim($Datos->nro_viajes)
            );
        }
        return response()->json($Lista);
    }
    
    public function crear_tabla_datos_generales_irizar(Request $request)
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

        $totalg = DB::select("select count(*) as total from taller.control_consumo() 
                                where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." and xveh_marca like '%MERCEDES%'");
        $sql = DB::select("select xcde_placa,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(sum(xcde_ahxviaje),3) as ahorro, round(sum(xcde_exxviaje),3) as exceso,
                                   (round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as totalae,count(xcde_placa) as nro_viajes from taller.control_consumo() 
                                    where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." and xveh_marca like '%MERCEDES%' group by xcde_placa order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);

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
        
        $array = array();
        $sum = DB::select("select count(xcde_placa) as sum from taller.control_consumo() where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." and xveh_marca like '%MERCEDES%' ");
        $array['sum'] = $sum[0]->sum;
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $Lista->userdata = $array;
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->xcde_placa;           
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->xcde_placa),
                trim($Datos->rendimiento),
                trim($Datos->ahorro),
                trim($Datos->exceso),
                trim($Datos->totalae),
                trim($Datos->nro_viajes)
            );
        }
        return response()->json($Lista);
    }
    
    public function traer_datos_saldo(Request $request)
    {
        $costoadblue = DB::table('taller.tblcostoadblue_coa')->select('coa_saldo')->where([['coa_anio',$request['anio']],['coa_mes',$request['mes']]])->get();
        if ($costoadblue->count() > 0) 
        {
            return $costoadblue;
        }
        else
        {
            return 0;
        }
    }
    
    public function crear_tabla_costo_optimo_ruta(Request $request)
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
        
        $costoadblue = DB::table('taller.tblcostoadblue_coa')->select('coa_saldo')->where([['coa_anio',$request['anio']],['coa_mes',$request['mes']]])->get();

        $totalg = DB::select("select count(*) as total from taller.control_consumo() 
                                where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." ");
        $sql = DB::select("SELECT xrut_id,xcde_ruta,xcde_consumo_deseado as cdg, round(avg(xcde_consumo_real),3) as cra, (xcde_consumo_deseado - round(avg(xcde_consumo_real),3)) as totalae, round(xcde_consumo_deseado * ".$costoadblue[0]->coa_saldo.",3) as costocdg,  
                                round(avg(xcde_consumo_real) * ".$costoadblue[0]->coa_saldo.",3) as costocra, (round(xcde_consumo_deseado * ".$costoadblue[0]->coa_saldo.",3) - round(avg(xcde_consumo_real) * ".$costoadblue[0]->coa_saldo.",3)) as costoae FROM taller.control_consumo()
                                where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." group by xrut_id,xcde_ruta,xcde_consumo_deseado order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);

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
            $Lista->rows[$Index]['id'] = $Datos->xcde_ruta;           
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->xcde_ruta),
                trim($Datos->cdg),
                trim($Datos->cra),
                trim($Datos->totalae),
                trim($Datos->costocdg),
                trim($Datos->costocra),
                trim($Datos->costoae)
            );
        }
        return response()->json($Lista);
    }
    
    public function crear_tabla_costo_gen_abast_ruta(Request $request)
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
        
        $costoadblue = DB::table('taller.tblcostoadblue_coa')->select('coa_saldo')->where([['coa_anio',$request['anio']],['coa_mes',$request['mes']]])->get();

        $totalg = DB::select("select count(*) as total from taller.control_consumo() 
                                where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." ");
        $sql = DB::select("select xcde_ruta, round(sum(xcde_ahxviaje),3) as ahorro,round(sum(xcde_ahxviaje) * ".$costoadblue[0]->coa_saldo.",3) as totalca,round(sum(xcde_exxviaje),3) as exceso, 
                            round(sum(xcde_exxviaje * ".$costoadblue[0]->coa_saldo."),3) as totalce, round((sum(xcde_ahxviaje) * ".$costoadblue[0]->coa_saldo.") + (sum(xcde_exxviaje) * ".$costoadblue[0]->coa_saldo."),3) as totalcae from taller.control_consumo()
                            where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." group by xcde_ruta ORDER BY SUBSTRING(".$sidx." FROM '([0-9]+)')::BIGINT ".$sord.", ".$sidx." limit ".$limit." offset ".$start);

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
            $Lista->rows[$Index]['id'] = $Datos->xcde_ruta;           
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->xcde_ruta),
                trim($Datos->ahorro),
                trim($Datos->totalca),
                trim($Datos->exceso),
                trim($Datos->totalce),
                trim($Datos->totalcae)
            );
        }
        return response()->json($Lista);
    }
    
    public function crear_tabla_costo_gen_abast_placa(Request $request)
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
        
        $costoadblue = DB::table('taller.tblcostoadblue_coa')->select('coa_saldo')->where([['coa_anio',$request['anio']],['coa_mes',$request['mes']]])->get();

        $totalg = DB::select("select count(*) as total from taller.control_consumo() 
                                where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." ");
        $sql = DB::select("select xcde_placa,(round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as sum,round((sum(xcde_ahxviaje) + sum(xcde_exxviaje)) * ".$costoadblue[0]->coa_saldo.",3) as tot
                                from taller.control_consumo() 
                                where extract(month from xcde_fecha) = ".$request['mes']." and extract(year from xcde_fecha) = ".$request['anio']." group by xcde_placa order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);

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
            $Lista->rows[$Index]['id'] = $Datos->xcde_placa;    
            if ($Datos->sum > 0) {
                $ahorro = $Datos->sum;
            }else{
                $ahorro = 0.000;
            }
            if ($Datos->tot > 0) {
                $costo_ahorro = $Datos->tot;
            }else{
                $costo_ahorro = 0.000;
            }
            
            if ($Datos->sum < 0) {
                $exceso = $Datos->sum;
            }else{
                $exceso = 0.000;
            }
            if ($Datos->tot < 0) {
                $costo_exceso = $Datos->tot;
            }else{
                $costo_exceso = 0.000;
            }
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->xcde_placa),
                $ahorro,
                $costo_ahorro,
                $exceso,
                $costo_exceso
            );
        }
        return response()->json($Lista);
    }
    
    public function reportes_generales(Request $request)
    {
        //echo "columa: " . $request['columna'] . " orden: " . $request['orden'] . " tipo: " .$request['tipo'] ;
        if ($request->session()->has('id_usuario') && $this->accesos() == 1)
        {
            switch ($request['tipo']) 
            {
                case 1:
                    if ($request['columna'] === 'xcde_ruta') 
                    {
                        $datos = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_ruta,round(avg(xcde_consumo_real),3) as consumo,round(avg(xcde_kilometraje),3) as kg,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(sum(xcde_ahxviaje),3) as ahorro, round(sum(xcde_exxviaje),3) as exceso, 
                                (round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as totalae,count(xcde_ruta) as nro_viajes"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']]])->groupBy('xcde_ruta')->orderBy(DB::raw("substring(".$request['columna']." from '([0-9]+)')::bigint ".$request['orden'].",".$request['columna']." "))->get();
                    }
                    else
                    {
                        $datos = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_ruta,round(avg(xcde_consumo_real),3) as consumo,round(avg(xcde_kilometraje),3) as kg,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(sum(xcde_ahxviaje),3) as ahorro, round(sum(xcde_exxviaje),3) as exceso, 
                                (round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as totalae,count(xcde_ruta) as nro_viajes"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']]])->groupBy('xcde_ruta')->orderBy($request['columna'],$request['orden'])->get();
                    }
                    if($datos->count() > 0)
                    {
                        $view = \View::make('adblue.reportes.vw_rep_datos_prom_general_1',compact('datos'))->render();
                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($view)->setPaper('a4');
                        return $pdf->download("DATOS PROMEDIOS GENERALES POR TODA LA RUTA".".pdf");
                    }
                    else
                    {
                        return "NO SE ENCONTRARON DATOS";
                    }
                    break;
                case 2:
                    $datos = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_placa,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(sum(xcde_ahxviaje),3) as ahorro, round(sum(xcde_exxviaje),3) as exceso,
                                   (round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as totalae,count(xcde_placa) as nro_viajes"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']],['xveh_marca','like','%SCANIA%']])
                                   ->groupBy('xcde_placa')->orderBy($request['columna'],$request['orden'])->get();
                    if($datos->count() > 0)
                    {
                        $view = \View::make('adblue.reportes.vw_rep_datos_prom_general_2',compact('datos'))->render();
                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($view)->setPaper('a4');
                        return $pdf->download("DATOS GENERALES POR PLACA".".pdf");
                    }
                    else
                    {
                        return "NO SE ENCONTRARON DATOS";
                    }
                    break;
                case 3:
                    $datos = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_placa,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(sum(xcde_ahxviaje),3) as ahorro, round(sum(xcde_exxviaje),3) as exceso,
                                   (round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as totalae,count(xcde_placa) as nro_viajes"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']],['xveh_marca','like','%MERCEDES%']])
                                   ->groupBy('xcde_placa')->orderBy($request['columna'],$request['orden'])->get();
                    if($datos->count() > 0)
                    {
                        $view = \View::make('adblue.reportes.vw_rep_datos_prom_general_3',compact('datos'))->render();
                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($view)->setPaper('a4');
                        return $pdf->download("DATOS GENERALES POR PLACA".".pdf");
                    }
                    else
                    {
                        return "NO SE ENCONTRARON DATOS";
                    }
                    break;
                case 4:
                    $costoadblue = DB::table('taller.tblcostoadblue_coa')->select('coa_saldo')->where([['coa_anio',$request['anio']],['coa_mes',$request['mes']]])->get();
                    if ($request['columna'] === 'xcde_ruta') 
                    {
                        $datos = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xrut_id,xcde_ruta,xcde_consumo_deseado as cdg, round(avg(xcde_consumo_real),3) as cra, (xcde_consumo_deseado - round(avg(xcde_consumo_real),3)) as totalae, round(xcde_consumo_deseado * ".$costoadblue[0]->coa_saldo.",3) as costocdg,round(avg(xcde_consumo_real) * ".$costoadblue[0]->coa_saldo.",3) as costocra,
                                    (round(xcde_consumo_deseado * ".$costoadblue[0]->coa_saldo.",3) - round(avg(xcde_consumo_real) * ".$costoadblue[0]->coa_saldo.",3)) as costoae"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']]])
                                    ->groupBy('xrut_id','xcde_ruta','xcde_consumo_deseado')->orderBy(DB::raw("substring(".$request['columna']." from '([0-9]+)')::bigint ".$request['orden'].",".$request['columna']." "))->get();
                    }
                    else
                    {
                        $datos = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xrut_id,xcde_ruta,xcde_consumo_deseado as cdg, round(avg(xcde_consumo_real),3) as cra, (xcde_consumo_deseado - round(avg(xcde_consumo_real),3)) as totalae, round(xcde_consumo_deseado * ".$costoadblue[0]->coa_saldo.",3) as costocdg,round(avg(xcde_consumo_real) * ".$costoadblue[0]->coa_saldo.",3) as costocra,
                                    (round(xcde_consumo_deseado * ".$costoadblue[0]->coa_saldo.",3) - round(avg(xcde_consumo_real) * ".$costoadblue[0]->coa_saldo.",3)) as costoae"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']]])
                                    ->groupBy('xrut_id','xcde_ruta','xcde_consumo_deseado')->orderBy($request['columna'],$request['orden'])->get();
                    }
                    if($datos->count() > 0 && $costoadblue->count() > 0)
                    {
                        $view = \View::make('adblue.reportes.vw_rep_datos_prom_general_4',compact('datos'))->render();
                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($view)->setPaper('a4');
                        return $pdf->download("COSTO OPTIMO GENERAL POR ABASTECIMIENTO EN RUTA".".pdf");
                    }
                    else
                    {
                        return "NO SE ENCONTRARON DATOS";
                    }
                    break;
                case 5:
                    $costoadblue = DB::table('taller.tblcostoadblue_coa')->select('coa_saldo')->where([['coa_anio',$request['anio']],['coa_mes',$request['mes']]])->get();
                    if ($request['columna'] === 'xcde_ruta') 
                    {
                        $datos = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_ruta, round(sum(xcde_ahxviaje),3) as ahorro,round(sum(xcde_ahxviaje) * ".$costoadblue[0]->coa_saldo.",3) as totalca,round(sum(xcde_exxviaje),3) as exceso,round(sum(xcde_exxviaje * ".$costoadblue[0]->coa_saldo."),3) as totalce, 
                                    round((sum(xcde_ahxviaje) * ".$costoadblue[0]->coa_saldo.") + (sum(xcde_exxviaje) * ".$costoadblue[0]->coa_saldo."),3) as totalcae"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']]])
                                    ->groupBy('xcde_ruta')->orderBy(DB::raw("substring(".$request['columna']." from '([0-9]+)')::bigint ".$request['orden'].",".$request['columna']." "))->get();
                    }
                    else
                    {
                        $datos = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_ruta, round(sum(xcde_ahxviaje),3) as ahorro,round(sum(xcde_ahxviaje) * ".$costoadblue[0]->coa_saldo.",3) as totalca,round(sum(xcde_exxviaje),3) as exceso,round(sum(xcde_exxviaje * ".$costoadblue[0]->coa_saldo."),3) as totalce, 
                                    round((sum(xcde_ahxviaje) * ".$costoadblue[0]->coa_saldo.") + (sum(xcde_exxviaje) * ".$costoadblue[0]->coa_saldo."),3) as totalcae"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']]])
                                    ->groupBy('xcde_ruta')->orderBy($request['columna'],$request['orden'])->get();
                    }
                    if($datos->count() > 0 && $costoadblue->count() > 0)
                    {
                        $view = \View::make('adblue.reportes.vw_rep_datos_prom_general_5',compact('datos'))->render();
                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($view)->setPaper('a4');
                        return $pdf->download("COSTO GENERAL  POR ABASTECIMIENTO EN RUTA".".pdf");
                    }
                    else
                    {
                        return "NO SE ENCONTRARON DATOS";
                    }
                    break;
                case 6:
                    if ($request['columna'] === 'ahorro' || $request['columna'] === 'exceso')
                    {
                        $new_columna = 'sum';
                    }
                    else if($request['columna'] === 'costo_ahorro' || $request['columna'] === 'costo_exceso')
                    {
                        $new_columna = 'tot';
                    }
                    else
                    {
                        $new_columna = 'xcde_placa';
                    }
                    $costoadblue = DB::table('taller.tblcostoadblue_coa')->select('coa_saldo')->where([['coa_anio',$request['anio']],['coa_mes',$request['mes']]])->get();
                    $datos = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_placa,(round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as sum,round((sum(xcde_ahxviaje) + sum(xcde_exxviaje)) * ".$costoadblue[0]->coa_saldo.",3) as tot"))
                                    ->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']]])
                                    ->groupBy('xcde_placa')->orderBy($new_columna,$request['orden'])->get();
                    if($datos->count() > 0 && $costoadblue->count() > 0)
                    {
                        $view = \View::make('adblue.reportes.vw_rep_datos_prom_general_6',compact('datos'))->render();
                        $pdf = \App::make('dompdf.wrapper');
                        $pdf->loadHTML($view)->setPaper('a4');
                        return $pdf->download("COSTO GENERAL POR ABASTECIMIENTO POR PLACA".".pdf");
                    }
                    else
                    {
                        return "NO SE ENCONTRARON DATOS";
                    }
                    break;
            }
        }
        else
        {
            return view('errors/vw_sin_permiso');
        }
    }
    
    public function reportes_informacion_excel(Request $request)
    {
        if ($request->session()->has('id_usuario') && $this->accesos() == 1)
        {
            $rep_1 = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_ruta,round(avg(xcde_consumo_real),3) as consumo,round(avg(xcde_kilometraje),3) as kg,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(sum(xcde_ahxviaje),3) as ahorro, round(sum(xcde_exxviaje),3) as exceso, 
                                (round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as totalae,count(xcde_ruta) as nro_viajes"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']]])->groupBy('xcde_ruta')
                                ->orderBy(DB::raw("substring(xcde_ruta from '([0-9]+)')::bigint asc,xcde_ruta "))->get();
            
            $rep_2 = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_placa,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(sum(xcde_ahxviaje),3) as ahorro, round(sum(xcde_exxviaje),3) as exceso,
                                   (round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as totalae,count(xcde_placa) as nro_viajes"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']],['xveh_marca','like','%SCANIA%']])
                                   ->groupBy('xcde_placa')->orderBy('xcde_placa','asc')->get();
            
            $rep_3 = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_placa,round(avg(xcde_rendimiento_lt),3) as rendimiento, round(sum(xcde_ahxviaje),3) as ahorro, round(sum(xcde_exxviaje),3) as exceso,
                                   (round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as totalae,count(xcde_placa) as nro_viajes"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']],['xveh_marca','like','%MERCEDES%']])
                                   ->groupBy('xcde_placa')->orderBy('xcde_placa','asc')->get();
            
            $costoadblue = DB::table('taller.tblcostoadblue_coa')->select('coa_saldo')->where([['coa_anio',$request['anio']],['coa_mes',$request['mes']]])->get();
            
            $rep_4 = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xrut_id,xcde_ruta,xcde_consumo_deseado as cdg, round(avg(xcde_consumo_real),3) as cra, (xcde_consumo_deseado - round(avg(xcde_consumo_real),3)) as totalae, round(xcde_consumo_deseado * ".$costoadblue[0]->coa_saldo.",3) as costocdg,round(avg(xcde_consumo_real) * ".$costoadblue[0]->coa_saldo.",3) as costocra,
                                    (round(xcde_consumo_deseado * ".$costoadblue[0]->coa_saldo.",3) - round(avg(xcde_consumo_real) * ".$costoadblue[0]->coa_saldo.",3)) as costoae"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']]])
                                    ->groupBy('xrut_id','xcde_ruta','xcde_consumo_deseado')->orderBy(DB::raw("substring(xcde_ruta from '([0-9]+)')::bigint asc,xcde_ruta "))->get();
            
            $rep_5 = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_ruta, round(sum(xcde_ahxviaje),3) as ahorro,round(sum(xcde_ahxviaje) * ".$costoadblue[0]->coa_saldo.",3) as totalca,round(sum(xcde_exxviaje),3) as exceso,round(sum(xcde_exxviaje * ".$costoadblue[0]->coa_saldo."),3) as totalce, 
                                    round((sum(xcde_ahxviaje) * ".$costoadblue[0]->coa_saldo.") + (sum(xcde_exxviaje) * ".$costoadblue[0]->coa_saldo."),3) as totalcae"))->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']]])
                                    ->groupBy('xcde_ruta')->orderBy('xcde_ruta','asc')->get();
            
            $rep_6 = DB::table(DB::raw('taller.control_consumo()'))->select(DB::raw("xcde_placa,(round(sum(xcde_ahxviaje),3) + round(sum(xcde_exxviaje),3)) as sum,round((sum(xcde_ahxviaje) + sum(xcde_exxviaje)) * ".$costoadblue[0]->coa_saldo.",3) as tot"))
                                    ->where([[DB::raw('extract(month from xcde_fecha)'),$request['mes']],[DB::raw('extract(year from xcde_fecha)'),$request['anio']]])
                                    ->groupBy('xcde_placa')->orderBy('xcde_placa','asc')->get();
            
            return Excel::download(new ReportesExport($rep_1,$rep_2,$rep_3,$rep_4,$rep_5,$rep_6), 'REPORTES GENERALES.xlsx');
        }
        else
        {
            return view('errors/vw_sin_permiso');
        }
    }
    
}
