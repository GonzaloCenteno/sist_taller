<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InicioController extends Controller
{
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu_registro = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',1]])->orderBy('menu_id','asc')->get();
            $menu_dashboard = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',2]])->orderBy('menu_id','asc')->get();
            return view('vw_inicio',compact('menu_registro','menu_dashboard'));
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }

    public function show($id, Request $request)
    {
        
    }

    public function create(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function edit($id_tipo_archivo,Request $request)
    {
        
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }

}
