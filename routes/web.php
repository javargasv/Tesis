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

Route::group(['middleware' => ['guest']], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('login', 'Auth\LoginController@login')->name('login');
    Route::get('register', 'Auth\RegisterController@showRegisterForm')->name('register');
    Route::post('register', 'Auth\RegisterController@create')->name('register/create');
});

Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout','Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::post('/notification/get', 'NotificationController@get');

    Route::group(['middleware' => ['Administrador']], function () {

        Route::get('/articulo','ArticuloController@index');
        Route::post('/articulo/registrar','ArticuloController@store');
        Route::put('/articulo/actualizar','ArticuloController@update');
        Route::put('/articulo/desactivar','ArticuloController@desactivar');
        Route::put('/articulo/activar','ArticuloController@activar');
        Route::get('/articulo/listarPdf','ArticuloController@listarPdf')->name('articulos_pdf');

        Route::get('/rol','RolController@index');
        Route::get('/rol/selectRol','RolController@selectRol');
        
        Route::get('/user','UserController@index');
        Route::post('/user/registrar','UserController@store');
        Route::put('/user/actualizar','UserController@update');
        Route::put('/user/desactivar','UserController@desactivar');
        Route::put('/user/activar','UserController@activar');
    });

    Route::group(['middleware' => ['Ejecutivo']], function () {

        Route::get('/dashboard','DashboardController');

        Route::get('/cliente','ClienteController@index');
        Route::post('/cliente/registrar','ClienteController@store');
        Route::put('/cliente/actualizar','ClienteController@update');
        Route::get('/cliente/selectCliente','ClienteController@selectCliente');


        Route::get('/venta','VentaController@index');
        Route::post('/venta/registrar','VentaController@store');
        Route::put('/venta/desactivar','VentaController@desactivar');
        Route::get('/venta/obtenerCabecera','VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles','VentaController@obtenerDetalles');
        Route::get('/venta/pdf/{id}','VentaController@pdf')->name('venta_pdf');
        Route::get('/venta/verCliente','VentaController@verCliente');
        Route::put('/venta/cambiarestado','VentaController@cambiarestado');

        Route::get('/articulo/buscarArticuloVenta','ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta','ArticuloController@listarArticuloVenta');


    });

    Route::group(['middleware' => ['Cliente']], function () {
        Route::get('/cliente','ClienteController@index');
        Route::post('/cliente/registrar','ClienteController@store');
        Route::put('/cliente/actualizar','ClienteController@update');
        Route::get('/cliente/selectCliente','ClienteController@selectCliente');


        Route::get('/venta','VentaController@index');
        Route::post('/venta/registrar','VentaController@store');
        Route::put('/venta/desactivar','VentaController@desactivar');
        Route::get('/venta/obtenerCabecera','VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles','VentaController@obtenerDetalles');
        Route::get('/venta/pdf/{id}','VentaController@pdf')->name('venta_pdf');
        Route::get('/venta/verCliente','VentaController@verCliente');
        

        Route::get('/articulo/buscarArticuloVenta','ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta','ArticuloController@listarArticuloVenta');

    });


});