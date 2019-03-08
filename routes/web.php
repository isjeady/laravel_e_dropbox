<?php

use App\Http\Controllers\HomeController;

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



Auth::routes();


Route::group(['middleware' => 'auth'],function(){    
    Route::get('/','HomeController@upload');
    Route::get('/home','HomeController@upload');
});


Route::group(['middleware' => ['auth','ajax'] , 'prefix' => 'api'],function(){    
    Route::get('/file/list', 'FileController@list');
    Route::get('/file/get/{idFile}', 'FileController@get');
    Route::delete('/file/delete/{idFile}', 'FileController@delete');
    Route::post('/file/store', 'FileController@store');
});