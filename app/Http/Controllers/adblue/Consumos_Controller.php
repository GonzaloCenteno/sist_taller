<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblconsumocabecera_cca;
use App\Http\Models\Tblconsumodetalle_cde;

class Consumos_Controller extends Controller
{   
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu_registro = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',1]])->orderBy('menu_id','asc')->get();
            $menu_dashboard = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',2]])->orderBy('menu_id','asc')->get();
            $estaciones = DB::table('taller.vw_estaciones')->get();
            $capacidad = DB::table('taller.tblcapacidad_cap')->orderby('cap_id','asc')->get();
            $placas = DB::table('taller.tblvehiculos_veh')->get();
            $tripulantes = DB::table('taller.tbltripulantes_tri')->select('tri_id','tri_nombre','tri_apaterno','tri_amaterno')->get();
            return view('adblue/vw_consumos',compact('menu_registro','menu_dashboard','estaciones','capacidad','placas','tripulantes'));
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
            if($request['show'] == 'datos')
            {
                return $this->recuperar_datos_consumos($id, $request);
            }
            if($request['show'] == 'generar_consumos')
            {
                return $this->recuperar_datos_crutas($id, $request);
            }
        }
        else
        {
            if($request['grid']=='consumos')
            {
                return $this->crear_tabla_consumos($request);
            }
            if($request['busqueda']=='personas')
            {
                return $this->autocompletar_personas($request);
            }
        }
    }
    
    public function autocompletar_personas(Request $request)
    {
        $conductores = DB::table('taller.tbltripulantes_tri')->get();
        $todo = array();
        foreach ($conductores as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->tri_id;
            $Lista->label = $Datos->tri_apaterno ." ". $Datos->tri_amaterno ." ". $Datos->tri_nombre;
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }

    public function create(Request $request)
    {
        
    }

    public function edit($cde_id,Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $Tblconsumodetalle_cde = new Tblconsumodetalle_cde;
                $detalle=  $Tblconsumodetalle_cde::where("cde_id","=",$cde_id)->first();
                if($detalle)
                {
                    $detalle->tri_idconductor = $request['tri_idconductor'];
                    $detalle->tri_idcopiloto = $request['tri_idcopiloto'];
                    $detalle->cde_fecha = $request['cde_fecha'];
                    $detalle->cde_kilometros = $request['cde_kilometros'];
                    $detalle->cde_observaciones = strtoupper($request['cde_observaciones']);
                    $detalle->cde_xtanque = $request['cde_xtanque'];
                    $detalle->cde_qlttanque = round($request['capacidad'] * ($request['cde_xtanque']/100),3);
                    $detalle->cde_xconsumida = 100 - $request['cde_xtanque'];
                    $detalle->cde_qltconsumida = round($request['capacidad'] * ($detalle->cde_xconsumida/100),3);
                    $detalle->cde_qabastecida = isset($request['cde_qabastecida']) ? round($request['cde_qabastecida'],3) : 0.0;
                    $detalle->cde_ingreso = $request['cde_ingreso'];
                    $detalle->cde_salida = $request['cde_salida'];
                    $detalle->cde_stop = $request['cde_stop'];
                    $detalle->cde_usumodificacion = session('id_usuario');
                    $detalle->cde_fecmodificacion = $request['descripcion'];
                    $detalle->save();
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

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       if ($request['tipo'] == 1) 
        {
            return $this->crear_datos_consumos($request);
        }
    }
    
    public function recuperar_datos_consumos($cde_id, Request $request)
    {
        $consumos = DB::table('taller.vw_consumos')->where('cde_id',$cde_id)->first();
        return response()->json($consumos);
    }
    
    public function recuperar_datos_crutas($rut_id, Request $request)
    {
        $rutas = DB::table('taller.vw_rutas_estaciones')->where('rut_id',$rut_id)->get();
        return $rutas;
    }

    public function crear_datos_consumos(Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $Tblconsumocabecera_cca = new Tblconsumocabecera_cca;
                $Tblconsumocabecera_cca->cca_fecregistro = date('d-m-Y');
                $Tblconsumocabecera_cca->save();

                $filas = count($request['contador']);

                for($i=0; $i<$filas; $i++)
                {
                    $Tblconsumodetalle_cde = new Tblconsumodetalle_cde;
                    $Tblconsumodetalle_cde->cde_fecha = isset($request['fecha'][$i]) ? $request['fecha'][$i] : date('d-m-Y');
                    $Tblconsumodetalle_cde->cca_id = $Tblconsumocabecera_cca->cca_id;
                    $Tblconsumodetalle_cde->veh_id = $request['cbx_placa'];
                    $Tblconsumodetalle_cde->rut_id = $request['cbx_consumo_ruta'];
                    $Tblconsumodetalle_cde->est_id = $request['estacion'][$i];
                    $Tblconsumodetalle_cde->tri_idconductor = isset($request['conductor'][$i]) ? $request['conductor'][$i] : 1;
                    $Tblconsumodetalle_cde->tri_idcopiloto = isset($request['piloto'][$i]) ? $request['piloto'][$i] : 1;
                    $Tblconsumodetalle_cde->cde_kilometros = isset($request['km'][$i]) ? $request['km'][$i] : 0;
                    $Tblconsumodetalle_cde->cde_xtanque = isset($request['xtanque'][$i]) ? $request['xtanque'][$i] : 0;
                    $Tblconsumodetalle_cde->cde_qlttanque = round($request['capacidad'] * ($request['xtanque'][$i]/100),3);
                    $Tblconsumodetalle_cde->cde_xconsumida = 100 - $request['xtanque'][$i];
                    $Tblconsumodetalle_cde->cde_qltconsumida = round($request['capacidad'] * ($Tblconsumodetalle_cde->cde_xconsumida/100),3);
                    $Tblconsumodetalle_cde->cde_qabastecida = isset($request['qabast'][$i]) ? round($request['qabast'][$i],3) : 0.0;
                    $Tblconsumodetalle_cde->cde_observaciones = isset($request['observ'][$i]) ? strtoupper($request['observ'][$i]) : '-';
                    $Tblconsumodetalle_cde->cde_ingreso = isset($request['ingreso'][$i]) ? round($request['ingreso'][$i],3) : 0.0;
                    $Tblconsumodetalle_cde->cde_salida = isset($request['salida'][$i]) ? round($request['salida'][$i],3) : 0.0;
                    $Tblconsumodetalle_cde->cde_stop = isset($request['stop'][$i]) ? round($request['stop'][$i],3) : 0.0;
                    $Tblconsumodetalle_cde->cde_fecregistro = date('d-m-Y');
                    $Tblconsumodetalle_cde->cde_anio = date('Y');
                    $Tblconsumodetalle_cde->save();
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
    
    public function crear_tabla_consumos(Request $request)
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

        $totalg = DB::table('taller.vw_consumos')->select(DB::raw('count(*) as total'))->get();
        $sql = DB::table('taller.vw_consumos')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
            $Lista->rows[$Index]['id'] = $Datos->cde_id;     
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->cde_id),
                trim($Datos->cde_fecha),
                trim($Datos->cca_id),
                trim($Datos->nro_vale),
                trim($Datos->veh_placa),
                trim($Datos->rut_descripcion),
                trim($Datos->est_descripcion),
                trim($Datos->conductor),
                trim($Datos->copiloto),
                trim($Datos->cde_kilometros),
                trim($Datos->cde_xtanque),
                trim($Datos->cde_qlttanque),
                trim($Datos->cde_xconsumida),
                trim($Datos->cde_qltconsumida),
                trim($Datos->cde_qabastecida),
                trim($Datos->cde_observaciones),
                trim($Datos->cde_ingreso),
                trim($Datos->cde_salida),
                trim($Datos->cde_stop),
            );
        }
        return response()->json($Lista);
    }
    
}
