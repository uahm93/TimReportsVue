<?php

Route::get('/', function () {
    return view('welcome');
});



Route::post('login/custom', [
    'uses' => 'LoginController@login',
    'as'   => 'login.custom'
]);

Route::get('login/custom', [
    'uses' => 'LoginController@salir',
    'as'   => 'salir'
]);
     
Route::get('/dashboard', 'DashboardController@index')->name('index');  


     


