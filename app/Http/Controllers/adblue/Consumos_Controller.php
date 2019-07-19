<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblconsumocabecera_cca;
use App\Http\Models\Tblconsumodetalle_cde;
use App\Http\Models\Tblconsumodetparcial_cdp;
use Validator;

class Consumos_Controller extends Controller
{   
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')],['ume_estado',1]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_consumo'],['btn_view',1]])->get();
            $capacidad = DB::table('taller.tblcapacidad_cap')->where('cap_estado',1)->orderby('cap_id','asc')->get();
            $placas = DB::table('taller.tblvehiculos_veh')->get();
            //$tripulantes = DB::table('taller.tbltripulantes_tri')->select('tri_id','tri_nombre','tri_apaterno','tri_amaterno')->get();
            if ($menu->count() > 0) 
            {
                $rol = DB::table('permisos.tblsistemasrol_sro')->select('sro_descripcion')->where('sro_id',$menu[0]->sro_id)->get();
            }
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('adblue/vw_consumos',compact('menu','capacidad','placas','tripulantes','permiso','rol'));
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
            if($request['show'] == 'datos_qparcial')
            {
                return $this->recuperar_datos_qparcial($id, $request);
            }
            if ($request['show'] == 'datos_estaciones') 
            {
                return $this->recuperar_datos_estaciones($id, $request);
            }
            if ($request['show'] == 'traer_datos_comentario') 
            {
                return $this->recuperar_datos_comentario($id, $request);
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
            if($request['datos']=='traer_estaciones')
            {
                return $this->traer_datos_estaciones($request);
            }
            if($request['datos'] == 'datos_nrovale')
            {
                return $this->recuperar_datos_nrovale($request);
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
            $Lista->label = $Datos->tri_nombre ." ". $Datos->tri_apaterno ." ". $Datos->tri_amaterno;
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }

    public function create(Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->crear_nueva_ruta_consumo_otr($request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->crear_nueva_ruta_consumo($request);
        }
    }

    public function edit($cde_id,Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->modificar_datos_consumos($cde_id,$request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->modificar_comentario_consumo($cde_id,$request);
        }
        if ($request['tipo'] == 3) 
        {
            return $this->modificar_estacion_consumo($cde_id,$request);
        }
        if ($request['tipo'] == 4) 
        {
            return $this->modificar_estado_comentario($cde_id,$request);
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
        if ($request['tipo'] == 2) 
        {
            return $this->crear_qabast_parciales($request);
        }
        if ($request['tipo'] == 3) 
        {
            return $this->modificar_qabast_parciales($request);
        }
        if ($request['tipo'] == 4) 
        {
            return $this->crear_nuevas_rutas_consumo($request);
        }
    }
    
    public function recuperar_datos_consumos($cde_id, Request $request)
    {
        $consumos = DB::table('taller.vw_consumos')->where('cde_id',$cde_id)->first();
        return response()->json($consumos);
    }
    
    public function recuperar_datos_crutas($rut_id, Request $request)
    {
        $rutas = DB::table('taller.vw_ruta_estacion')->where('rut_id',$rut_id)->get();
        return $rutas;
    }
    
    public function recuperar_datos_qparcial($cde_id, Request $request)
    {
        $qparcial = DB::table('taller.tblconsumodetparcial_cdp')->select('cdp_qparcial')->where('cde_id',$cde_id)->get();
        return $qparcial;
    }
    
    public function traer_datos_estaciones(Request $request)
    {
        $estaciones = DB::table('taller.vw_estaciones')->get();
        return $estaciones;
    }
    
    public function recuperar_datos_nrovale(Request $request)
    {
        $nrovale = DB::table('taller.vw_consumos')->select('cca_id','rut_descripcion','rut_id','veh_id')->where('cca_nrovale',$request['nrovale'])->orderBy('cde_id','DESC')->take(1)->first();
        if ($nrovale) 
        {
            return response()->json($nrovale);
        }
        else
        {
            return 0;
        }
    }

    public function crear_datos_consumos(Request $request)
    {
        if($request->ajax())
        {
            $validator = Validator::make($request->all(), [
                'km.*' => 'integer|nullable',
                'xtanque.*' => 'integer|nullable',
                'qabast.*' => 'numeric|between:0,9999.999|nullable',
                'ingreso.*' => 'numeric|between:0,9999.999|nullable',
                'salida.*' => 'numeric|between:0,9999.999|nullable',
                'stop.*' => 'numeric|between:0,9999.999|nullable',
            ],[
                'km.*.integer' => 'LOS CAMPOS DE LA COLUMNA "KILOMETROS" NO DEBEN CONTENER DECIMALES',
                'xtanque.*.integer' => 'LOS CAMPOS DE LA COLUMNA "%STOP EN TANQUE" NO DEBEN CONTENER DECIMALES',
                'qabast.*.between' => 'REVISAR LOS CAMPOS DE LA COLUMNA "Q-ABAST" UNO O MAS, EXCEDIERON SU CAPACIDAD NUMERICA',
                'ingreso.*.between' => 'REVISAR LOS CAMPOS DE LA COLUMNA "INGRESO" UNO O MAS, EXCEDIERON SU CAPACIDAD NUMERICA',
                'salida.*.between' => 'REVISAR LOS CAMPOS DE LA COLUMNA "SALIDA" UNO O MAS, EXCEDIERON SU CAPACIDAD NUMERICA',
                'stop.*.between' => 'REVISAR LOS CAMPOS DE LA COLUMNA "STOP" UNO O MAS, EXCEDIERON SU CAPACIDAD NUMERICA',
            ]);

            if ($validator->passes()) 
            {
                $error = null;

                DB::beginTransaction();
                try{
                    $consulta = DB::table('taller.tblconsumocabecera_cca')->orderBy('cca_id','DESC')->take(1)->first();
                    if ($consulta) 
                    {
                        $nrovale = $consulta->cca_nrovale + 1;
                    }
                    else
                    {
                        $nrovale = 1;
                    }
                    $Tblconsumocabecera_cca = new Tblconsumocabecera_cca;
                    $Tblconsumocabecera_cca->cca_nrovale = $nrovale;
                    $Tblconsumocabecera_cca->cca_fecregistro = date('Y-m-d H:i:s');
                    $Tblconsumocabecera_cca->save();

                    $filas = count($request['contador']);

                    $rutasestacion = DB::table('taller.vw_consumo_deseado_optimo')->where('rut_id',$request['cbx_consumo_ruta'])->get();
                    $contador = 1;
                    for($i=0; $i<$filas; $i++)
                    {
                        $Tblconsumodetalle_cde = new Tblconsumodetalle_cde;
                        $Tblconsumodetalle_cde->cde_fecha = isset($request['fecha'][$i]) ? $request['fecha'][$i] : date('d-m-Y');
                        $Tblconsumodetalle_cde->cca_id = $Tblconsumocabecera_cca->cca_id;
                        $Tblconsumodetalle_cde->veh_id = $request['cbx_placa'];
                        $Tblconsumodetalle_cde->rut_id = $request['cbx_consumo_ruta'];
                        $Tblconsumodetalle_cde->est_id = isset($request['estacion'][$i]) ? $request['estacion'][$i] : 1;
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
                        $Tblconsumodetalle_cde->cde_fecregistro = date('Y-m-d H:i:s');
                        $Tblconsumodetalle_cde->cde_anio = date('Y');
                        $Tblconsumodetalle_cde->cde_consumo = isset($request['consumo'][$i]) ? round($request['consumo'][$i],3) : 0.0;
                        $Tblconsumodetalle_cde->cde_condeseado = $rutasestacion[0]->consumo_deseado;
                        $Tblconsumodetalle_cde->cde_montoptimo = $rutasestacion[0]->monto_optimo;
                        $Tblconsumodetalle_cde->cde_orden = $contador++;
                        $Tblconsumodetalle_cde->cde_usucreacion = session('id_usuario');
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
            else
            {
                return response()->json([
                'msg' => 'validator',
                'error'=>$validator->errors()->all()
                ]);
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
        
        if ($request['indice'] == 0) 
        {
            $totalg = DB::table('taller.vw_consumos')->select(DB::raw('count(*) as total'))->get();
            $sql = DB::table('taller.vw_consumos')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        }
        else
        {
            if(isset($request["nrovale"]))
            {
                $vale = trim($request['nrovale']);
            }
            else
            {
                $vale = "";
            }
            if(isset($request["placa"]))
            {
                $placa = strtoupper(trim($request['placa']));
            }    
            else
            {
                $placa = "";
            }
            if(isset($request["fdesde"]) && isset($request["fhasta"]))
            {
                $fdesde = $request['fdesde'];
                $fhasta = $request['fhasta'];
            }    
            else
            {
                $fdesde = "";
                $fhasta = "";
            }
            if(isset($request["ruta"]))
            {
                $ruta = strtoupper(trim($request['ruta']));
            }    
            else
            {
                $ruta = "";
            }
            if(isset($request["estacion"]))
            {
                $estacion = strtoupper(trim($request['estacion']));
            }    
            else
            {
                $estacion = "";
            }
            
            $where="WHERE 1=1";
            if($vale!='')
            {
                $where.= " AND cca_nrovale = '$vale'";
            }
                    
            if($placa!='')
            {
                $where.= " AND veh_placa LIKE '%$placa%'";
            }
            
            if($fdesde!='' && $fhasta!='')
            {
                $where.= " AND cde_fecha between '$fdesde' and '$fhasta'";
            }
            
            if($ruta!='')
            {
                $where.= " AND rut_descripcion LIKE '%$ruta%'";
            }
            
            if($estacion!='')
            {
                $where.= " AND est_descripcion LIKE '%$estacion%'";
            }

            $totalg = DB::select("select count(*) as total from taller.vw_consumos ".$where);
            $sql = DB::select("select * from taller.vw_consumos ".$where." order by ".$sidx." ".$sord." limit ".$limit." offset ".$start);
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
        $nuevo = 0;
        foreach ($sql as $Index => $Datos) {
            //$menus = DB::table('taller.tblconsumocabecera_cca')->get();
            if ($nuevo == $Datos->cca_id) 
            {
                $marcas = 'otro';
            }
            else
            {
                $nuevo = $Datos->cca_id;
                $marcas = 'agregar_estaciones';
            }
            $Lista->rows[$Index]['id'] = $Datos->cde_id;     
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->cde_id),
                trim($Datos->cde_qparcial),
                'modificar_estacion',
                $marcas,
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
                trim($Datos->est_id),
                trim($Datos->cde_comentario),
                trim($Datos->cde_coment_est),
                trim($Datos->veh_id)
            );
        }
        return response()->json($Lista);
    }
    
    public function crear_qabast_parciales(Request $request)
    {
        if($request->ajax())
        {
            $validator = Validator::make($request->all(), [
                'cdp_qparcial.*' => 'required|max:8',
            ],[
                'cdp_qparcial.*.required' => 'EL CAMPO "Q-ABAST PARCIAL" ES OBLIGATORIO',
                'cdp_qparcial.*.max' => 'EL CAMPO "Q-ABAST PARCIAL" DEBE TENER COMO MAXIMO 8 DIGITOS',
            ]);

            if ($validator->passes()) 
            {
                $error = null;

                DB::beginTransaction();
                try{
                    $inputs = count($request['contarqabastParcial']);

                    for($i=0; $i<$inputs; $i++)
                    {
                        $Tblconsumodetparcial_cdp = new Tblconsumodetparcial_cdp;
                        $Tblconsumodetparcial_cdp->cde_id = $request['cde_id'];
                        $Tblconsumodetparcial_cdp->cdp_qparcial = isset($request['cdp_qparcial'][$i]) ? round($request['cdp_qparcial'][$i],3) : 0.0;
                        $Tblconsumodetparcial_cdp->cdp_usucreacion = session('id_usuario');
                        $Tblconsumodetparcial_cdp->save();
                    }

                    $Tblconsumodetalle_cde = new Tblconsumodetalle_cde;
                    $Qparcial = DB::table('taller.tblconsumodetparcial_cdp')->select(DB::raw("sum(cdp_qparcial) as total"))->where('cde_id',$request['cde_id'])->get();
                    $detalle = $Tblconsumodetalle_cde::where("cde_id","=",$request['cde_id'])->first();
                    if($detalle)
                    {
                        $detalle->cde_qparcial = 1;
                        $detalle->cde_qabastecida = round($Qparcial[0]->total,3);
                        $detalle->cde_usumodificacion = session('id_usuario');
                        $detalle->cde_fecmodificacion = date('Y-m-d H:i:s');
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
            else
            {
                return response()->json([
                'msg' => 'validator',
                'error'=>$validator->errors()->all()
                ]);
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
    
    public function modificar_qabast_parciales(Request $request)
    {
        if($request->ajax())
        {
            $validator = Validator::make($request->all(), [
                'cdp_qparcial.*' => 'required|max:8',
            ],[
                'cdp_qparcial.*.required' => 'EL CAMPO "Q-ABAST PARCIAL" ES OBLIGATORIO',
                'cdp_qparcial.*.max' => 'EL CAMPO "Q-ABAST PARCIAL" DEBE TENER COMO MAXIMO 8 DIGITOS',
            ]);
            
            if ($validator->passes()) 
            {
            
                $error = null;

                DB::beginTransaction();
                try{
                    $inputs = count($request['contarqabastParcial']);

                    for($i=0; $i<$inputs; $i++)
                    {
                        $Tblconsumodetparcial_cdp = new Tblconsumodetparcial_cdp;
                        $consumodetparcial = $Tblconsumodetparcial_cdp::where("cde_id","=",$request['cde_id'])->first();
                        if($consumodetparcial)
                        {
                            $consumodetparcial->cdp_qparcial = round($request['cdp_qparcial'][$i],3);
                            $consumodetparcial->cdp_usumodificacion = session('id_usuario');
                            $consumodetparcial->cdp_fecmodificacion = date('Y-m-d H:i:s');
                            $consumodetparcial->save();
                        }
                    }

                    $Tblconsumodetalle_cde = new Tblconsumodetalle_cde;
                    $Qparcial = DB::table('taller.tblconsumodetparcial_cdp')->select(DB::raw("sum(cdp_qparcial) as total"))->where('cde_id',$request['cde_id'])->get();
                    $detalle = $Tblconsumodetalle_cde::where("cde_id","=",$request['cde_id'])->first();
                    if($detalle)
                    {
                        $detalle->cde_qparcial = 1;
                        $detalle->cde_qabastecida = round($Qparcial[0]->total,3);
                        $detalle->cde_usumodificacion = session('id_usuario');
                        $detalle->cde_fecmodificacion = date('Y-m-d H:i:s');
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
            else
            {
                return response()->json([
                'msg' => 'validator',
                'error'=>$validator->errors()->all()
                ]);
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
    
    public function modificar_datos_consumos($cde_id,Request $request)
    {
        if($request->ajax())
        {
            $validator = Validator::make($request->all(), [
                'cde_kilometros' => 'required|integer',
                'cde_xtanque' => 'required|integer',
                'cde_qabastecida'=> 'required|numeric|between:0,9999.999',
                'cde_ingreso'=> 'required|numeric|between:0,9999.999',
                'cde_salida'=> 'required|numeric|between:0,9999.999',
                'cde_stop'=> 'required|numeric|between:0,9999.999',
            ],[
                'cde_kilometros.integer' => 'EL CAMPO "KILOMETROS" NO PUEDE CONTENER NUMEROS DECIMALES',
                'cde_xtanque.integer' => 'EL CAMPO "%STOP EN TANQUE" NO PUEDE CONTENER NUMEROS DECIMALES',
                'cde_qabastecida.between' => 'EL "CAMPO Q-ABAST" DESBORDA SU CAPACIDAD NUMERICA',
                'cde_ingreso.between' => 'EL CAMPO "INGRESO" DESBORDA SU CAPACIDAD NUMERICA',
                'cde_salida.between' => 'EL CAMPO "SALIDA" DESBORDA SU CAPACIDAD NUMERICA',
                'cde_stop.between' => 'EL CAMPO "STOP" DESBORDA SU CAPACIDAD NUMERICA',
            ]);
            
            if ($validator->passes()) 
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
                        $detalle->cde_fecmodificacion = date('Y-m-d H:i:s');
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
            else
            {
                return response()->json([
                    'msg' => 'validator',
                    'error'=>$validator->errors()->all()
                ]);
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
    
    public function modificar_comentario_consumo($cde_id,Request $request)
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
                    $detalle->cde_comentario = strtoupper(trim($request['cde_comentario']));
                    $detalle->cde_coment_est = 1;
                    $detalle->cde_usumodificacion = session('id_usuario');
                    $detalle->cde_fecmodificacion = date('Y-m-d H:i:s');
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
            return response()->json([
                'msg' => $success,
                'respuesta' => DB::table('taller.tblconsumodetalle_cde')->select('cde_comentario')->where('cde_id',$detalle->cde_id)->first() 
            ]);
        }
        else
        {
            return $error;
        }
    }
    
    public function modificar_estacion_consumo($cde_id,Request $request)
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
                    $detalle->est_id = $request['est_id'];
                    $detalle->cde_usumodificacion = session('id_usuario');
                    $detalle->cde_fecmodificacion = date('Y-m-d H:i:s');
                    $detalle->save();
                }
                $Tblconsumodetalle_cde::where('cca_id',$detalle->cca_id)->update([
                    'rut_id' => 19,
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
    
    public function recuperar_datos_estaciones($cca_id,Request $request)
    {
        $estaciones = DB::table('taller.vw_consumos')->select('cde_id','est_id','est_descripcion')->where('cca_id',$cca_id)->orderBy('cde_orden','asc')->get();
        return $estaciones;
    }
    
    public function crear_nuevas_rutas_consumo(Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $Tblconsumodetalle_cde = new Tblconsumodetalle_cde;
                $filas = count($request['vw_consumos_cde_id']);
                $contador = 1;
                for($i=0; $i<$filas; $i++)
                {
                    $detalle=  $Tblconsumodetalle_cde::where("cde_id","=",$request['vw_consumos_cde_id'][$i])->first();
                    $detalle->rut_id = 19;
                    $detalle->cde_orden = $contador++;
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
    
    public function modificar_estado_comentario($cde_id,Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                DB::table('taller.tblconsumodetalle_cde')->where('cde_id',$cde_id)->update([
                    'cde_coment_est' => 0,
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
    
    public function recuperar_datos_comentario($cde_id,Request $request)
    {
        return DB::table('taller.tblconsumodetalle_cde')->select('cde_comentario')->where('cde_id',$cde_id)->get();
    }
    
    public function crear_nueva_ruta_consumo_otr(Request $request)
    {
        if($request->ajax())
        {
            $validator = Validator::make($request->all(), [
                'cde_kilometros' => 'required|integer',
                'cde_xtanque' => 'required|integer',
                'cde_qabastecida'=> 'required|numeric|between:0,9999.999',
                'cde_ingreso'=> 'required|numeric|between:0,9999.999',
                'cde_salida'=> 'required|numeric|between:0,9999.999',
                'cde_stop'=> 'required|numeric|between:0,9999.999',
            ],[
                'cde_kilometros.integer' => 'EL CAMPO "KILOMETROS" NO PUEDE CONTENER NUMEROS DECIMALES',
                'cde_xtanque.integer' => 'EL CAMPO "%STOP EN TANQUE" NO PUEDE CONTENER NUMEROS DECIMALES',
                'cde_qabastecida.between' => 'EL CAMPO "Q-ABAST" DESBORDA SU CAPACIDAD NUMERICA',
                'cde_ingreso.between' => 'EL CAMPO "INGRESO" DESBORDA SU CAPACIDAD NUMERICA',
                'cde_salida.between' => 'EL CAMPO "SALIDA" DESBORDA SU CAPACIDAD NUMERICA',
                'cde_stop.between' => 'EL CAMPO "STOP" DESBORDA SU CAPACIDAD NUMERICA',
            ]);
            
            if ($validator->passes()) 
            {
                $error = null;

                DB::beginTransaction();
                try{
                    $orden = DB::table('taller.tblconsumodetalle_cde')->select('cde_orden')->where('cca_id',$request['cca_id'])->orderby('cde_orden','desc')->take(1)->get();
                    $Tblconsumodetalle_cde = new Tblconsumodetalle_cde;
                    $Tblconsumodetalle_cde->cde_fecha = $request['cde_fecha'];
                    $Tblconsumodetalle_cde->cca_id = $request['cca_id'];
                    $Tblconsumodetalle_cde->veh_id = $request['veh_id'];
                    $Tblconsumodetalle_cde->rut_id = $request['rut_id'];
                    $Tblconsumodetalle_cde->est_id = $request['est_id'];
                    $Tblconsumodetalle_cde->tri_idconductor = $request['tri_idconductor'];
                    $Tblconsumodetalle_cde->tri_idcopiloto = $request['tri_idcopiloto'];
                    $Tblconsumodetalle_cde->cde_kilometros = $request['cde_kilometros'];
                    $Tblconsumodetalle_cde->cde_xtanque = $request['cde_xtanque'];
                    $Tblconsumodetalle_cde->cde_qlttanque = round($request['capacidad'] * ($request['cde_xtanque']/100),3);
                    $Tblconsumodetalle_cde->cde_xconsumida = 100 - $request['cde_xtanque'];
                    $Tblconsumodetalle_cde->cde_qltconsumida = round($request['capacidad'] * ($Tblconsumodetalle_cde->cde_xconsumida/100),3);
                    $Tblconsumodetalle_cde->cde_qabastecida = $request['cde_qabastecida'];
                    $Tblconsumodetalle_cde->cde_observaciones = strtoupper($request['cde_observaciones']);
                    $Tblconsumodetalle_cde->cde_ingreso = $request['cde_ingreso'];
                    $Tblconsumodetalle_cde->cde_salida = $request['cde_salida'];
                    $Tblconsumodetalle_cde->cde_stop = $request['cde_stop'];
                    $Tblconsumodetalle_cde->cde_fecregistro = date('Y-m-d H:i:s');
                    $Tblconsumodetalle_cde->cde_anio = date('Y');
                    $Tblconsumodetalle_cde->cde_consumo = 0.0;
                    $Tblconsumodetalle_cde->cde_orden = $orden[0]->cde_orden + 1;
                    $Tblconsumodetalle_cde->cde_usucreacion = session('id_usuario');
                    $Tblconsumodetalle_cde->save();

                    $success = 1;
                    DB::commit();
                } catch (\Exception $ex) {
                    $success = 2;
                    $error = $ex->getMessage();
                    DB::rollback();
                }
            }
            else
            {
                return response()->json([
                    'msg' => 'validator',
                    'error'=>$validator->errors()->all()
                ]);
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
    
    public function crear_nueva_ruta_consumo(Request $request)
    {
        if($request->ajax())
        {
            $error = null;

            DB::beginTransaction();
            try{
                $Tblconsumodetalle_cde = new Tblconsumodetalle_cde;
                $Tblconsumodetalle_cde->cde_fecha = date('d-m-Y');
                $Tblconsumodetalle_cde->cca_id = $request['cca_id'];
                $Tblconsumodetalle_cde->veh_id = $request['veh_id'];
                $Tblconsumodetalle_cde->est_id = $request['est_id'];
                $Tblconsumodetalle_cde->rut_id = 19;
                $Tblconsumodetalle_cde->cde_usucreacion = session('id_usuario');
                $Tblconsumodetalle_cde->cde_fecregistro = date('Y-m-d H:i:s');
                $Tblconsumodetalle_cde->cde_anio = date('Y');
                $Tblconsumodetalle_cde->save();
                
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
                'msg' => $success,
                'respuesta' => DB::table('taller.vw_consumos')->select('cde_id','est_id','est_descripcion')->where('cde_id',$Tblconsumodetalle_cde->cde_id)->first()
            ]);
        }
        else
        {
            return $error;
        }     
    }
    
}
