<?php

namespace App\Http\Controllers\grifo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PDF;

class Grifo_Reportes_Controller extends Controller
{
    public function accesos()
    {
        $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_reportes_grifo'],['btn_view',1]])->get();
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
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')],['ume_estado',1]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_reportes_grifo'],['btn_view',1]])->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('grifo/vw_grifo_reportes',compact('menu','permiso'));
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
            if($request['reportes'] == 'consumo_mensual')
            {
                return $this->reporte_consumo_mensual($request);
            }
            if($request['reportes'] == 'consumo_xdia')
            {
                return $this->reporte_consumo_xdia($request);
            }
        }
    }
    
    public function reporte_consumo_mensual(Request $request)
    {
        if ($request->session()->has('id_usuario') && $this->accesos() == 1)
        {
            $meses = DB::select("select extract(month from vca_fecha) as mes,to_char(vca_fecha, 'TMMonth'::text) AS mes_descripcion,sum(vde_cantidad) as total from grifo.vw_vales
                                        where vca_estado = 1 and extract(month from vca_fecha) not in(4,5)
                                        group by extract(month from vca_fecha),to_char(vca_fecha, 'TMMonth'::text) order by mes");
            if (count($meses) > 0) 
            {
                $view = \View::make('grifo.reportes.vw_consumo_mensual',compact('meses'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("CONSUMO MENSUAL".".pdf");
            }
            else
            {
                return "NO SE ENCONTRARON DATOS";
            }
        }
        else
        {
            return view('errors/vw_sin_permiso');
        }
    }
    
    public function reporte_consumo_xdia(Request $request)
    {
        if ($request->session()->has('id_usuario') && $this->accesos() == 1)
        {
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $datos = DB::table("grifo.vw_vales")->whereBetween('vca_fecha', [$request['fecha_inicio'],$request['fecha_fin']])->where('vca_estado',1)->orderBy('vca_fecha','desc')->get();
            if ($datos->count() > 0) 
            {
                $view = \View::make('grifo.reportes.vw_consumo_x_dias2', ['datos'=>$datos]);
                $html_content = $view->render();

                PDF::setHeaderCallback(function($pdf) {
                    $pdf->SetY(7);
                    $pdf->SetFont('helvetica', 'N', 10);
                    $pdf->Cell(50, 0, 'TRANSPORTES CROMOTEX', 0, false, 'C', 0, '', 0, false, 'M', 'M');
                    $pdf->Cell(0, 0, date('d-m-Y'), 0, 1, 'R');
                });

                PDF::setFooterCallback(function($pdf) {
                    $pdf->SetY(-15);
                    //FUENTE B - I
                    $pdf->SetFont('helvetica', 'N', 10);
                    $pdf->Cell(0, 10, 'Pagina '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
                });

                PDF::SetAuthor('CROMOTEX');
                PDF::SetTitle('CONSUMOS EN GENERAL');
                PDF::SetSubject('REPORTE DE CONSUMOS EN GENERAL');
                PDF::SetMargins(7, 18, 7);
                PDF::SetFont('times', 'N', 12);
                PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);     
                PDF::AddPage('L', 'A4');
                PDF::writeHTML($html_content, true, false, true, false, '');
                PDF::lastPage();
                PDF::Output('CONSUMOS.pdf');
            }
            else
            {
                return "NO SE ENCONTRARON DATOS";
            }   
        }
        else
        {
            return view('errors/vw_sin_permiso');
        }
    }
}
