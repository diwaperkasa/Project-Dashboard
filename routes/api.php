<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('task')->group(function () {
    Route::get('/list', 'TaskController@list');
    Route::post('/', 'TaskController@create');
    Route::delete('/{id}', 'TaskController@delete');
    Route::patch('/{id}', 'TaskController@update');
});