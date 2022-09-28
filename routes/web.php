<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/veiculos/marcas', function(){
        return view('veiculos.marcas');
    })
    ->name('marcas')
    ->middleware('auth');

Route::get('/veiculos/modelos', function(){
        return view('veiculos.modelos');
    })
    ->name('modelos')
    ->middleware('auth');

Route::get('/veiculos/carros', function(){
        return view('veiculos.carros');
    })
    ->name('carros')
    ->middleware('auth');