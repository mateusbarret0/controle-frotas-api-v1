<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
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

Route::post('auth/login', 'App\Http\Controllers\AuthController@login');


Route::post('insert/veiculos', 'App\Http\Controllers\VeiculoController@insertVeiculos');
Route::get('veiculos', 'App\Http\Controllers\VeiculoController@getVeiculos');
Route::post('edit/veiculos', 'App\Http\Controllers\VeiculoController@editVeiculos');
Route::post('edit/status/veiculo', 'App\Http\Controllers\VeiculoController@editStatusVeiculo');
Route::post('delete/veiculos', 'App\Http\Controllers\VeiculoController@deleteVeiculos');
// Route::post('insert/rota', 'App\Http\Controllers\VeiculoController@insertRotas');

Route::post('insert/usuario', 'App\Http\Controllers\UsuarioController@insertUsuario');
Route::get('usuarios', 'App\Http\Controllers\UsuarioController@getUsuarios');
Route::post('edit/usuario', 'App\Http\Controllers\UsuarioController@editUsuarios');
Route::post('delete/usuario', 'App\Http\Controllers\UsuarioController@deleteUsuarios');

Route::post('insert/rotas', 'App\Http\Controllers\RotasController@insertRotas');
Route::post('update/obs/rota', 'App\Http\Controllers\RotasController@updateObsRotas');
Route::get('busca/cep/{cep}', 'App\Http\Controllers\RotasController@buscarEndereco');
Route::get('get/rotas', 'App\Http\Controllers\RotasController@getRotas');
Route::post('edit/status/rota', 'App\Http\Controllers\RotasController@editStatusRota');
