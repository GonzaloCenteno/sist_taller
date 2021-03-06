<?php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Request $request) {
    if ($request->session()->has('id_usuario'))
    {
        return redirect('consumo');
    }
    else
    {
        return view('autenticacion/login');  
    }
});

Route::get('adm', function () {
    return view('dashboard/vw_dashboard');
});

Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::resource('inicio', 'InicioController');

Route::group(['namespace' => 'adblue'], function() 
{
    Route::resource('estacion', 'Estaciones_Controller');
    Route::resource('ruta', 'Rutas_Controller');
    Route::resource('ruta_estacion', 'Ruta_Estacion_Controller');
    Route::resource('consumo', 'Consumos_Controller');
    Route::resource('control', 'Control_Controller');
    Route::resource('vehiculos', 'Vehiculos_Controller');
    Route::get('control_diario','Control_Controller@abrir_rep_control_diario')->name('control_diario');
    Route::get('control_abastecimiento','Control_Controller@abrir_rep_control_abast')->name('control_abastecimiento');
    Route::get('control_abast_xplaca/{est_id}/{veh_id}','Control_Controller@abrir_rep_control_abast_xplaca')->name('control_abast_xplaca');
    //Route::get('control_consumo_rep/{anio}/{mes}','Control_Controller@abrir_rep_control_consumo')->name('control_consumo_rep');
    Route::resource('control_consumo', 'Control_Consumo_Controller');
    Route::resource('capacidad', 'Capacidad_Controller');
    Route::resource('costo_adblue', 'Costo_Adblue_Controller');
});

Route::group(['namespace' => 'grifo'], function() 
{
    Route::resource('almacen_combustible', 'Almacen_Combustible_Controller');
    Route::resource('vales_grifo', 'Grifo_Vales_Controller');
    Route::resource('reportes_grifo', 'Grifo_Reportes_Controller');
});

Route::group(['namespace' => 'programacion'], function() 
{
    Route::resource('viajes', 'Viajes_Controller');
});