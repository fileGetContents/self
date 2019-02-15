<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function () {
    Route::any('test', 'TestController@index');
    Route::any('test2', 'TestController@index2');

    Route::group(['prefix' => 'good'], function () {
        Route::any('home_list', 'GoodController@getHomeList'); // 首页商品列表哦
        Route::any('base_info', 'GoodController@getBaseInfo'); // 商品详细
        Route::any('page', 'GoodController@page'); // 商品详细

    });

    Route::group(['prefix' => 'banana'], function () {
        Route::any('get', 'BananaController@get');             // 获取banana列表
    });

    Route::group(['prefix' => 'group'], function () {
        Route::any('create', 'GroupController@create');        // 创建分组
        Route::any('good/by/id', 'GroupController@goodById');  // 获取商品参团
        Route::any('has/group', 'GroupController@hasGroup');   // 是否创建一个拼团
    });

    Route::group(['prefix' => 'order'], function () {
        Route::any('ready', 'OrderController@ready');          // 支付账单确认
        Route::any('create', 'OrderController@create');        // 创建订单
        Route::any('orders', 'OrderController@orders');        // 用户订单
    });

    Route::group(['prefix' => 'user'], function () {
        Route::any('sign', 'UserController@sign');             // 注册用户
        Route::any('activation', 'UserController@activation')->name('userActivation'); // 激活用户
        Route::any('login', 'UserController@login');           // 用户登录
    });

    Route::group(['prefix' => 'address'], function () {
        Route::any('get', 'AddressController@get');             // 获取收货地址
        Route::any('update/default', 'AddressController@updateDefault'); // 设置默认收货地址
        Route::any('add', 'AddressController@add');             // 增加默认收货地址
        Route::any('del', 'AddressController@del');             // 删除默认收货地址
    });
});
