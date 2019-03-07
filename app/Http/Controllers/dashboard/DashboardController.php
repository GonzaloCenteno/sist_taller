<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {     
        return view('dashboard/vw_dashboard');
    }

    public function show($id, Request $request)
    {
        
    }

    public function create(Request $request)
    {
        return datatables(DB::table('usuarios'))->toJson();
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
