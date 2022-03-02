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

Route::get('/auth/register',[\App\Http\Controllers\Backend\AuthController::class, 'register']);
Route::any('/auth/login',[\App\Http\Controllers\Backend\AuthController::class, 'login']);
Route::get('/auth/userinfo',[\App\Http\Controllers\Backend\AuthController::class, 'userinfo']);
/*
Route::group([

    'middleware' => 'backend',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});*/
