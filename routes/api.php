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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('jenis-barang')->group(function () {
    Route::get('/', 'App\Http\Controllers\JenisBarangController@get');
    Route::get('/{id}', 'App\Http\Controllers\JenisBarangController@show');
    Route::post('/', 'App\Http\Controllers\JenisBarangController@store');
    Route::delete('/{id}', 'App\Http\Controllers\JenisBarangController@destroy');
    Route::put('/{id}', 'App\Http\Controllers\JenisBarangController@update');
});

Route::prefix('barang')->group(function () {
    Route::get('/', 'App\Http\Controllers\BarangController@get');
    Route::get('/{id}', 'App\Http\Controllers\BarangController@show');
    Route::post('/', 'App\Http\Controllers\BarangController@store');
    Route::delete('/{id}', 'App\Http\Controllers\BarangController@destroy');
    Route::put('/{id}', 'App\Http\Controllers\BarangController@update');
});

Route::prefix('transaksi')->group(function () {
    Route::get('/', 'App\Http\Controllers\TransaksiController@get');
    Route::get('/{id}', 'App\Http\Controllers\TransaksiController@show');
    Route::post('/', 'App\Http\Controllers\TransaksiController@store');
    Route::delete('/{id}', 'App\Http\Controllers\TransaksiController@destroy');
    Route::put('/{id}', 'App\Http\Controllers\TransaksiController@update');
});
