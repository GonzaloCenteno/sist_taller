<?php

namespace App\Http\Controllers\grifo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblbombas_bom;
use App\Http\Models\Tblestacion_est;
use App\Http\Models\Tblcentrocostos_cec;
use App\Http\Models\Tblalmacenes_alm;
use App\Http\Models\Tblvehiculos_veh;
use App\Http\Models\Tblvalecabecera_vca;
use App\Http\Models\Tblvaledetalle_vde;
use App\Http\Models\Tblgrifo_gri;

class Almacen_Combustible_Controller extends Controller
{   
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')],['ume_estado',1]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_almacen_combustible'],['btn_view',1]])->get();
            $bombas = Tblbombas_bom::orderBy('bom_id','asc')->get();
            $estaciones = Tblestacion_est::orderBy('est_id','asc')->get();
            $ctr_costo = Tblcentrocostos_cec::where('cec_id',1)->first();
            $almacen = Tblalmacenes_alm::where('alm_id',1)->first();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('grifo/vw_almacen_general',compact('menu','permiso','bombas','estaciones','ctr_costo','almacen'));
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
            if ($request['show'] == 'imprimir_vale') 
            {
                return $this->imprimir_vales($id,$request);
            }
            if ($request['show'] == 'verificar_vale') 
            {
                return $this->verificar_vale($id,$request);
            }
        }
        else
        {
            if ($request['busqueda'] == 'vehiculos') 
            {
                return $this->recuperar_datos_vehiculos($request);
            }
            if ($request['busqueda'] == 'trp_nrodoc') 
            {
                return $this->recuperar_datos_tripulantes_nrodoc($request);
            }
            if ($request['busqueda'] == 'trp_nombres') 
            {
                return $this->recuperar_datos_tripulantes_nombres($request);
            }
            if ($request['datos'] == 'datos_nrovale') 
            {
                return $this->recuperar_datos_nrovale($request);
            }
            if ($request['tabla'] == 'tblvaledetalle_vde') 
            {
                return $this->crear_tabla_valesdetalle($request);
            }
            if ($request['datos'] == 'datos_topkat') 
            {
                return $this->recuperar_datos_topkat($request);
            }
            if ($request['seleccionar'] == 'datos_placa') 
            {
                return $this->buscar_informacion_placa($request);
            }
            if ($request['tabla'] == 'tblgrifo_gri') 
            {
                return $this->recuperar_datos_tabla_valesdetalle($request);
            }
            if ($request['tabla'] == 'tblvales') 
            {
                return $this->crear_tabla_tblvales($request);
            }
        }
    }

    public function create(Request $request)
    {
        
    }

    public function edit($cap_id,Request $request)
    {
        
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
        if($request['tipo'] == 1)
        {
            return $this->crear_vale($request);
        }
        if($request['tipo'] == 2)
        {
            return $this->editar_vale($request);
        }
    }
    
    public function recuperar_datos_vehiculos(Request $request)
    {
        $vehiculos = DB::table('taller.tblvehiculos_veh')->where('veh_placa', 'like', '%'.strtoupper($request['datos']).'%')->get();
        $todo = array();
        foreach ($vehiculos as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->veh_id;
            $Lista->label = $Datos->veh_placa;
            $Lista->veh_vehiculo = $Datos->veh_clase ." ". $Datos->veh_marca ." ". $Datos->mod_nombre ." ". $Datos->cat_nombre;
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
    
    public function recuperar_datos_tripulantes_nrodoc(Request $request)
    {
        $tripulantes = DB::table('taller.tbltripulantes_tri')->where('tri_nrodoc','like','%'.$request['nro_doc'].'%')->get();
        $todo = array();
        foreach ($tripulantes as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->tri_id;
            $Lista->label = $Datos->tri_nrodoc;
            $Lista->tripulante = $Datos->tri_nombre ." ". $Datos->tri_apaterno ." ". $Datos->tri_amaterno;
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
    
    public function recuperar_datos_tripulantes_nombres(Request $request)
    {
        $tripulantes = DB::table('taller.tbltripulantes_tri')
                        ->where('tri_nombre','like','%'.strtoupper($request['nombres']).'%')
                        ->orWhere('tri_apaterno','like','%'.strtoupper($request['nombres']).'%')
                        ->orWhere('tri_amaterno','like','%'.strtoupper($request['nombres']).'%')
                        ->get();
        $todo = array();
        foreach ($tripulantes as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->tri_id;
            $Lista->label = $Datos->tri_nombre ." ". $Datos->tri_apaterno ." ". $Datos->tri_amaterno;
            $Lista->documento = $Datos->tri_nrodoc;
            array_push($todo, $Lista);
        }
        return response()->json($todo); 
    }
    
    public function recuperar_datos_nrovale(Request $request)
    {
        $vales = DB::table('grifo.vw_vale_cabecera')->where('vca_numvale',$request['nrovale'])->first();
        if ($vales) 
        {
            return response()->json([
                'msg' => 1,
                'vca_id' => $vales->vca_id,
                'vale_cntmtri' => $vales->vca_cntmtri,
                'vale_cntmtrf' => $vales->vca_cntmtrf,
                'vale_klmtrj' => $vales->vca_kilometraje,
                'vale_estado' => $vales->vca_estado,
                'vale_bomba' => $vales->bom_id,
                'vale_numvale' => $vales->vca_numvale,
                'tri_id' => $vales->tri_id,
                'tripulante' => $vales->tripulante,
                'dni' => $vales->tri_nrodoc,
                'veh_id' => $vales->veh_id,
                'est_id' => $vales->est_id,
                'vehiculo' => $vales->vehiculo,
                'veh_placa' => $vales->veh_placa,
                'vale_referencia' => $vales->vca_referencia,
                'vale_fecemision' => \Carbon\Carbon::parse($vales->vca_fecemision)->format('d/m/Y'),
            ]);
        }
        else
        {
            return response()->json([
                'msg' => 0
            ]);
        }
    }
    
    public function crear_tabla_valesdetalle(Request $request)
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
        
        $totalg = DB::select("select count(*) as total from grifo.vw_vale_detalle where vca_numvale = ".$request['vale_numvale']);
        $sql = DB::select("select * from grifo.vw_vale_detalle where vca_numvale = ".$request['vale_numvale']);

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
            $Lista->rows[$Index]['id'] = $Datos->codigo;      
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->codigo),
                trim($Datos->descripcion),
                trim($Datos->unidad),
                trim($Datos->cantidad),
                trim($Datos->tanquep),
                trim($Datos->tanques),
                trim($Datos->tanquea)
            );
        }
        return response()->json($Lista);
    }
    
    public function recuperar_datos_topkat(Request $request)
    {        
        $indice = Tblgrifo_gri::select('gri_id','gri_placa')->where([['gri_fecha','=',date('Y-m-d')],['gri_estado',0]])->orderBy('gri_trans','asc')->first();
        if ($indice) 
        {
            $vehiculo = Tblvehiculos_veh::select('veh_id','veh_placa','veh_marca','veh_clase')->where('veh_placa','like','%'.substr($indice->gri_placa, 2).'%')->get();
            if ($vehiculo->count() > 0) 
            {
                return response()->json([
                    'data' => $vehiculo,
                    'id_trans' => $indice->gri_id,
                ]);
            }
            else
            {
                return response()->json([
                    'msg' => 1,
                ]);
            }
        }
        else
        {
            return response()->json([
                'msg' => 0,
            ]);
        }
    }
    
    public function buscar_informacion_placa(Request $request)
    {
        $correlativo = Tblvalecabecera_vca::select('vca_numvale')->orderBy('vca_numvale','desc')->take(1)->first();
        $tran_historica = Tblgrifo_gri::where('gri_id',$request['id_trans'])->first();
        $vca_cntmtrf = Tblvalecabecera_vca::select('vca_cntmtrf')->where('bom_id',$tran_historica->gri_tanque)->orderBy('vca_numvale','desc')->take(1)->first();
        $vehiculo = DB::table('grifo.vw_vale_cabecera')->where('veh_id',$request['veh_id'])->orderBy('vca_numvale','desc')->take(1)->first();    
        
        if(!isset($vehiculo))
        {
            return 0;
        }
        
        return response()->json([
            'vale' => $correlativo->vca_numvale + 1,
            'veh_id' => $vehiculo->veh_id,
            'veh_vehiculo' => $vehiculo->vehiculo,
            'veh_placa' => $vehiculo->veh_placa,
            'vale_cntmtri' => $vca_cntmtrf->vca_cntmtrf,
            'vca_bomba' => intval($tran_historica->gri_tanque),
            'kilometraje' => $vehiculo->vca_kilometraje,
            'vale_cntmtrf' => round($vca_cntmtrf->vca_cntmtrf + (double)$tran_historica->gri_consumo), // (double)number_format($Datos->trh_transactionquantity,0,",","."),
            'referencia' => $vehiculo->vca_referencia,
            'vale_estado' => $vehiculo->vca_estado,
            'id_trans' => $request['id_trans']
        ]);
    }
    
    public function recuperar_datos_tabla_valesdetalle(Request $request)
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
        
        $totalg = DB::select("select count(*) as total from grifo.tblgrifo_gri where gri_id=".$request['id_trans']);
        $sql = Tblgrifo_gri::where([['gri_id',$request['id_trans']],['gri_estado',0]])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
            $Lista->rows[$Index]['id'] = '0180010001019';      
            $Lista->rows[$Index]['cell'] = array(
                '0180010001019',
                'PETROLEO DIESEL 2',
                'GL',
                (double)$Datos->gri_consumo,
                (double)$Datos->gri_consumo,
                0,
                0
            );
        }
        return response()->json($Lista);
    }
    
    public function imprimir_vales($vca_id,Request $request)
    {
        $vale_cabecera = DB::table('grifo.vw_vale_cabecera')->where('vca_id',$vca_id)->first();
        $vale_detalle = DB::table('grifo.vw_vale_detalle')->where('vca_numvale',$vale_cabecera->vca_numvale)->first();
        $sucursal = DB::table('grifo.tblsucursales_suc')->first();
        $almacen = DB::table('grifo.tblalmacenes_alm')->first();
        if ($vale_cabecera && $vale_detalle) 
        {
            $view = \View::make('grifo.reportes.vw_vale',compact('sucursal','vale_cabecera','vale_detalle','almacen'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4','landscape');
            \Storage::put('public/vales/'.$vale_cabecera->vca_numvale, $pdf->output());
            return $pdf->stream("ABASTECIMIENTO VALE N° ".$vale_cabecera->vca_numvale." ".".pdf");
        }
        else
        {
            return "NO SE ENCONTRARON DATOS PARA ESTE N°-VALE";
        }
    }
    
    public function verificar_vale($vca_id, Request $request)
    {
        $sql = Tblvalecabecera_vca::select('vca_numvale')->where('vca_id',$vca_id)->first();
        $data = storage_path('app/public/vales/' . $sql->vca_numvale);
        if(file_exists($data))
        {
            return response()->json([
                'msg' => 1,
                'ruta' => chunk_split(base64_encode(file_get_contents($data)))
            ]);
        }
        else
        {
            return 0;
        }
        
        //****CREAR PDFS****//
//        $datos = Tblvalecabecera_vca::select('vca_id')->where('vca_id','>',1221)->get();
//        $contador = Tblvalecabecera_vca::count();
//        for($i = 0; $i < $contador; $i++)
//        {
//            $vale_cabecera = DB::table('grifo.vw_vale_cabecera')->where('vca_id',$datos[$i]->vca_id)->first();
//            $vale_detalle = DB::table('grifo.vw_vale_detalle')->where('vca_numvale',$vale_cabecera->vca_numvale)->first();
//            $sucursal = DB::table('grifo.tblsucursales_suc')->first();
//            $almacen = DB::table('grifo.tblalmacenes_alm')->first();
//
//            
//            set_time_limit(0);
//            ini_set('memory_limit', '5G');
//            $view = \View::make('grifo.reportes.vw_vale',compact('sucursal','vale_cabecera','vale_detalle','almacen'))->render();
//            $pdf = \App::make('dompdf.wrapper');
//            $pdf->loadHTML($view)->setPaper('a4','landscape');
//            \Storage::put('public/vales/'.$vale_cabecera->vca_numvale, $pdf->output());
//        }
//        return 1;
    }
    
    public function crear_vale(Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                
                $topkat = Tblgrifo_gri::where('gri_id',$request['id_trans'])->first();
                if($topkat)
                {
                    $topkat->gri_estado = 1;
                    $topkat->save();
                }
                
                $Tblvalecabecera_vca = new Tblvalecabecera_vca;
                $Tblvalecabecera_vca->suc_id            = 1;
                $Tblvalecabecera_vca->alm_id            = 1;
                $Tblvalecabecera_vca->tri_id            = $request['hiddentxt_tri_nrodoc'];
                $Tblvalecabecera_vca->veh_id            = $request['hiddentxt_veh_placa'];
                $Tblvalecabecera_vca->vca_numvale       = $request['txt_vca_numvale'];
                $Tblvalecabecera_vca->vca_fecemision    = $request['txt_vca_fecemision'].' '.date('H:i:s');
                $Tblvalecabecera_vca->vca_referencia    = trim($request['txt_referencia']) ? $request['txt_referencia'] : '-';
                $Tblvalecabecera_vca->vca_cntmtri       = $request['txt_cntmtri'];
                $Tblvalecabecera_vca->vca_cntmtrf       = $request['txt_cntmtrf'];
                $Tblvalecabecera_vca->vca_kilometraje   = $request['txt_kilometraje'];
                $Tblvalecabecera_vca->bom_id            = $request['cbx_bomba'];
                $Tblvalecabecera_vca->est_id            = $request['cbx_ruta_area'];
                $Tblvalecabecera_vca->vca_usucreacion   = session('id_usuario');
                $Tblvalecabecera_vca->vca_fecha         = $topkat->gri_fecha;
                $Tblvalecabecera_vca->vca_hora          = $topkat->gri_hora;
                $Tblvalecabecera_vca->save();
                
                $Tblvaledetalle_vde = new Tblvaledetalle_vde;
                $Tblvaledetalle_vde->vca_id             = $Tblvalecabecera_vca->vca_id;
                $Tblvaledetalle_vde->pro_id             = 1;
                $Tblvaledetalle_vde->vde_cantidad       = $request['cantidad'];
                $Tblvaledetalle_vde->vde_tanquep        = $request['cantidad'];
                $Tblvaledetalle_vde->vde_tanques        = 0;
                $Tblvaledetalle_vde->vde_tanquea        = 0;
                $Tblvaledetalle_vde->vde_usucreacion    = session('id_usuario');
                $Tblvaledetalle_vde->save();
                
                $vale_cabecera = DB::table('grifo.vw_vale_cabecera')->where('vca_id',$Tblvalecabecera_vca->vca_id)->first();
                $vale_detalle = DB::table('grifo.vw_vale_detalle')->where('vca_numvale',$vale_cabecera->vca_numvale)->first();
                $sucursal = DB::table('grifo.tblsucursales_suc')->first();
                $almacen = DB::table('grifo.tblalmacenes_alm')->first();
                $view = \View::make('grifo.reportes.vw_vale',compact('sucursal','vale_cabecera','vale_detalle','almacen'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4','landscape');
                \Storage::put('public/vales/'.$vale_cabecera->vca_numvale, $pdf->output());

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
            return response()->json([
                'msg' => 1,
                'vca_id' => $Tblvalecabecera_vca->vca_id,
                'pdf' => chunk_split(base64_encode(file_get_contents(storage_path('app/public/vales/' . $vale_cabecera->vca_numvale))))
            ]);
        }
        else
        {
            return $error;
        }
    }
    
    public function editar_vale(Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                Tblvalecabecera_vca::where('vca_id',$request['vca_id'])->update([
                    'tri_id'                =>  $request['hiddentxt_tri_nrodoc'],
                    'veh_id'                =>  $request['hiddentxt_veh_placa'],
                    'vca_fecemision'        =>  $request['txt_vca_fecemision'].' '.date('H:i:s'),
                    'vca_referencia'        =>  trim($request['txt_referencia']) ? $request['txt_referencia'] : '-',
                    'vca_kilometraje'       =>  $request['txt_kilometraje'],
                    'bom_id'                =>  $request['cbx_bomba'],
                    'est_id'                =>  $request['cbx_ruta_area'],
                    'vca_usumodificacion'   =>  session('id_usuario'),
                    'vca_fecmodificacion'   =>  date('Y-m-d H:i:s')
                ]);
                
                $vale_cabecera = DB::table('grifo.vw_vale_cabecera')->where('vca_id',$request['vca_id'])->first();
                $vale_detalle = DB::table('grifo.vw_vale_detalle')->where('vca_numvale',$vale_cabecera->vca_numvale)->first();
                $sucursal = DB::table('grifo.tblsucursales_suc')->first();
                $almacen = DB::table('grifo.tblalmacenes_alm')->first();
                $view = \View::make('grifo.reportes.vw_vale',compact('sucursal','vale_cabecera','vale_detalle','almacen'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4','landscape');
                \Storage::put('public/vales/'.$vale_cabecera->vca_numvale, $pdf->output());

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
            return response()->json([
                'msg' => 1,
                'vca_id' => $request['vca_id'],
                'pdf' => chunk_split(base64_encode(file_get_contents(storage_path('app/public/vales/' . $vale_cabecera->vca_numvale))))
            ]);
        }
        else
        {
            return $error;
        }
    }
    
    public function crear_tabla_tblvales(Request $request)
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
        
        if ($request['indice'] == '0') 
        {
            $totalg = DB::select("select count(*) as total from grifo.vw_vale_cabecera");
            $sql = DB::table("grifo.vw_vale_cabecera")->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
            
            $totalg = DB::select("select count(*) as total from grifo.vw_vale_cabecera ".$where." and vca_estado = 1");
            $sql = DB::select("select * from grifo.vw_vale_cabecera ".$where." and vca_estado = 1 order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);
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
            $Lista->rows[$Index]['id'] = $Datos->vca_numvale;          
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->vca_numvale),
                trim($Datos->tri_nrodoc),
                trim($Datos->tripulante),
                trim($Datos->veh_placa),
                trim($Datos->vca_kilometraje),
                trim($Datos->vca_fecha),
            );
        }
        return response()->json($Lista);
    }
    
}
