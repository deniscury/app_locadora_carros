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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('login', 'AuthController@login');
Route::post('refresh', 'AuthController@refresh');

// Adiciona um prefix para versionamento da url das apis

Route::prefix('v1')->middleware('jwt.auth')->group(function(){
    Route::apiResource('marca', 'MarcaController');
    Route::apiResource('modelo', 'ModeloController');
    Route::apiResource('carro', 'CarroController');
    Route::apiResource('cliente', 'ClienteController');
    Route::apiResource('locacao', 'LocacaoController');
    Route::post('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');
});

