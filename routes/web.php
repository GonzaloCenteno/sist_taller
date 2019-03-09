<?php

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

Route::get('/', function () {
    return view('autenticacion/login');
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
});