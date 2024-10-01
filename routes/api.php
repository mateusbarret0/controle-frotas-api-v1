<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VeiculoController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('veiculos', 'App\Http\Controllers\VeiculoController@getVeiculos');
Route::post('insert/veiculos', 'App\Http\Controllers\VeiculoController@insertVeiculos');
Route::post('delete/veiculos', 'App\Http\Controllers\VeiculoController@deleteVeiculos');
Route::post('edit/veiculos', 'App\Http\Controllers\VeiculoController@editVeiculos');