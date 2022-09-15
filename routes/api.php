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

Route::post('/register', 'App\Http\Controllers\UserController@registration');
Route::post('/login', 'App\Http\Controllers\UserController@login');
Route::post('/info', 'App\Http\Controllers\UserController@user')->middleware('auth:sanctum');
Route::post('/edit', 'App\Http\Controllers\UserController@edit')->middleware('auth:sanctum');
Route::post('/delete', 'App\Http\Controllers\UserController@destroy')->middleware('auth:sanctum');
