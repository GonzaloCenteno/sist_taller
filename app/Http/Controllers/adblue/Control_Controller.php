<?php

namespace App\Http\Controllers\adblue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tblestacion_est;

class Control_Controller extends Controller
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
            $menu_dashboard = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',2]])->orderBy('menu_id','asc')->get();
            return view('adblue/vw_control',compact('menu_registro','menu_dashboard'));
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
        
    }

    public function edit($est_id,Request $request)
    {
        
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }

}
