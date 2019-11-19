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


//用户添加路由
Route::get('user/add','UserController@add');

//用户执行添加路由
Route::post('user/store','UserController@store');

//用户列表页的路由
Route::get('user/index','UserController@index');

//用户修改路由
Route::get('user/edit/{id}','UserController@edit');

//用户执行修改路由
Route::post('user/doEdit','UserController@doEdit');

//用户执行删除路由
Route::get('user/del/{id}','UserController@del');