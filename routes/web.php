<?php

Route::get('/', function () {
    return view('welcome');
});



Route::post('dashboard', [
    'uses' => 'LoginController@login',
    'as'   => 'login'
]);

Route::get('logout', [
    'uses' => 'LoginController@salir',
    'as'   => 'salir'
]);

//Protección de rutas
Route::group(['middleware' => ['cliente']], function () {



    //RUTAS HISTORICOS
    Route::resource('/historicos', 'HistoricosController');

    Route::get('totales/', [
        'uses' => 'HistoricosController@totales',
        'as'   => 'totales'
    ]);

    Route::get('t_ayer/', [
        'uses' => 'HistoricosController@t_ayer',
        'as'   => 't_ayer'
    ]);

    Route::get('t_hoy/', [
        'uses' => 'HistoricosController@t_hoy',
        'as'   => 't_hoy'
    ]);

    Route::get('pendientes_hoy/', [
        'uses' => 'HistoricosController@pendientes_hoy',
        'as'   => 'pendientes_hoy'
    ]);

    Route::get('grafica/', [
        'uses' => 'HistoricosController@grafica',
        'as'   => 'grafica'
    ]);

    Route::get('totales_complementos/', [
        'uses' => 'HistoricosController@totales_complementos',
        'as'   => 'totales_complementos'
    ]);

    Route::get('promedio_historico/', [
        'uses' => 'HistoricosController@promedio_historico',
        'as'   => 'promedio_historico'
    ]);
    Route::get('grafica_historica/{contador}', [
        'uses' => 'HistoricosController@graficaHistorica',
        'as'   => 'graficaHistorica'
    ]);



    //RUTAS BITACORA

    Route::get('/Bitacora', 'DashboardController@index')->name('index');

    Route::get('/bitacora', [
        'uses' => 'BitacoraController@index',
        'as'   => 'vista_bitacora'
    ]);

    Route::get('/FiltroBitacora/{rfc_emisor}/{uuid}/{estado}/{error}',  [
        'uses' => 'BitacoraController@bitacora',
        'as'   => 'bitacora'
    ]);

    Route::get('/subCuentasBita/{rfc_emisor}/{uuid}/{estado}/{error}',  [
        'uses' => 'BitacoraController@subCuentasBita',
        'as'   => 'subCuentasBita'
    ]);

    Route::get('/excel/{tipo}',  [
        'uses' => 'BitacoraController@excel',
        'as'   => 'excel'
    ]);

    Route::get('/xml/{ruta}/{uuid}',  [
        'uses' => 'BitacoraController@xml',
        'as'   => 'xml'
    ]);


    //RUTAS BOVEDA  
    Route::get('/Boveda', 'DashboardController@index')->name('index');

    Route::get('/boveda/{fechainicio}/{fechafin}/{estado}',  [
        'uses' => 'BovedaController@boveda',
        'as'   => 'boveda'
    ]);

    Route::get('/bovedaSubcuentas/{fechainicio}/{fechafin}/{estado}',  [
        'uses' => 'BovedaController@bovedaSubcuentas',
        'as'   => 'bovedaSubcuentas'
    ]);

    Route::get('/excelBoveda/{tipo}',  [
        'uses' => 'BovedaController@excelBoveda',
        'as'   => 'excelBoveda'
    ]);

    Route::get('/zipBoveda/{tipo}',  [
        'uses' => 'BovedaController@zipBoveda',
        'as'   => 'zipBoveda'
    ]);

    // RUTAS LCO

    Route::get('/Lco', 'DashboardController@index')->name('index');

    Route::get('/lco/{rfc}/{certificado}/{estado}',  [
        'uses' => 'LcoController@lco',
        'as'   => 'lco'
    ]);

    Route::get('/Inscritos/{rfc}/',  [
        'uses' => 'LcoController@inscritos',
        'as'   => 'Inscritos'
    ]);

    Route::get('/excelLco',  [
        'uses' => 'LcoController@excelLco',
        'as'   => 'excelLco'
    ]);


    //RUTAS REPORTES
    Route::get('/Inscritos', 'DashboardController@index')->name('index');
    Route::get('/Facturas', 'DashboardController@index')->name('index');
    Route::get('/Reportes', 'DashboardController@index')->name('index');
    Route::get('/ReportesE', 'DashboardController@index')->name('index');
    Route::get('/dashboard', 'DashboardController@index')->name('index');

    Route::get('/emisores',  [
        'uses' => 'ReportesController@emisores',
        'as'   => 'emisores'
    ]);

    Route::get('/totalEmisor/{fechainicio}/{fechafin}/{estado}',  [
        'uses' => 'ReportesController@totalEmisor',
        'as'   => 'totalEmisor'
    ]);

    Route::get('/totalRFCEmisor/{fechainicio}/{fechafin}/{estado}',  [
        'uses' => 'ReportesController@totalRFCEmisor',
        'as'   => 'totalEmisor'
    ]);

    Route::get('/iuUsuario/',  [
        'uses' => 'ReportesController@iuUsuario',
        'as'   => 'iuUsuario'
    ]);

    Route::get('/excelReportes',  [
        'uses' => 'ReportesController@excelReportes',
        'as'   => 'excelReportes'
    ])->middleware('cliente');


    //FACTURAS

    Route::get('/obtenerUsuario',  [
        'uses' => 'UsuarioController@obtenerUsuario',
        'as'   => 'obtenerUsuario'
    ]);

    Route::get('/obtenerNotificacion',  [
        'uses' => 'UsuarioController@obtenerNotificaciones',
        'as'   => 'obtenerNotificacion'
    ]);

    Route::get('/ultimaFactura',  [
        'uses' => 'UsuarioController@ultimaFactura',
        'as'   => 'ultimaFactura'
    ]);

    Route::get('/obtenerFacturas',  [
        'uses' => 'UsuarioController@obtenerFacturas',
        'as'   => 'obtenerFacturas'
    ]);

    Route::get('/descargarXml/{ruta}/{fecha}',  [
        'uses' => 'UsuarioController@descargarXml',
        'as'   => 'descargarXml'
    ]);

    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        return "Cache is cleared";
    });
});


//Midleware SUPER USUARIO
Route::group(['middleware' => ['superUser']], function () {

     Route::get('/superuser', 'DashboardController@superuser')->name('superuser');
     Route::post('/accesoTotal', 'DashboardController@accesoTotal')->name('accesoTotal');

});
