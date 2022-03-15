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
/*用户角色*/
Route::get('/auth/roles',[\App\Http\Controllers\Backend\AuthController::class, 'roles']);
Route::get('/auth/routes',[\App\Http\Controllers\Backend\AuthController::class, 'routes']);
Route::get('/schedule/index',[\App\Http\Controllers\Backend\MainController::class, 'index']);
Route::get('/auth/register',[\App\Http\Controllers\Backend\AuthController::class, 'register']);
Route::any('/auth/login',[\App\Http\Controllers\Backend\AuthController::class, 'login']);
Route::post('/auth/logout',[\App\Http\Controllers\Backend\AuthController::class, 'logout']);
Route::post('/auth/getCode',[\App\Http\Controllers\Backend\AuthController::class, 'getCode']);
Route::get('/auth/userinfo',[\App\Http\Controllers\Backend\AuthController::class, 'userinfo']);
Route::post('/auth/reset',[\App\Http\Controllers\Backend\AuthController::class, 'reset']);
/*订单*/
Route::get('/order/list',[\App\Http\Controllers\Backend\OrderController::class, 'list']);
Route::post('/order/getPageList',[\App\Http\Controllers\Backend\OrderController::class, 'getPageList']);
Route::post('/order/updateOrderStatus',[\App\Http\Controllers\Backend\OrderController::class, 'updateOrderStatus']);
Route::post('/order/getShipInfo',[\App\Http\Controllers\Backend\OrderController::class, 'getShipInfo']);
/*商品*/
Route::get('/goods/getCateList',[\App\Http\Controllers\Backend\GoodsController::class, 'getCateList']);
Route::get('/goods/getParentCateList',[\App\Http\Controllers\Backend\GoodsController::class, 'getParentCateList']);
/*首页*/
Route::get('/main/count',[\App\Http\Controllers\Backend\MainController::class, 'count']);

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
