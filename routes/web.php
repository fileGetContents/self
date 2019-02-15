<?php

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
Route::any('test', 'TestController@index');
Route::any('test2', 'TestController@index2');
Route::any('mail', 'TestController@mail');



Route::group(['prefix' => 'group'], function () {
    Route::any('create', 'V1\GroupController@create');
});
Route::any('/users/{id}/update_email_notify', 'UsersController@updateEmailNotify')->name('users.update_email_notify');
