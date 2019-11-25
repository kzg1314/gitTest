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

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function () {
    //后台登录路由
    Route::get('login','LoginController@login');
    //验证码路由
    Route::get('code','LoginController@code');
    //执行登录路由
    Route::post('doLogin','LoginController@doLogin');
    //加密算法路由
    Route::get('jiami','LoginController@jiami');
});

Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>'isLogin'],function () {
    //后台首页路由
    Route::get('index','LoginController@index');
    //后台欢迎页路由
    Route::get('welcome','LoginController@welcome');
    //后台退出路由
    Route::get('logout','LoginController@logout');

    //用户后台模块相关路由
    Route::get('user/del','UserController@delAll');
    Route::get('user/auth/{id}','UserController@auth');
    Route::post('user/doauth','UserController@doAuth');
    Route::resource('user','UserController');

    //角色模块路由
    Route::get('role/del','RoleController@delAll');
    Route::get('role/auth/{id}','RoleController@auth');
    Route::post('role/doauth','RoleController@doAuth');
    Route::resource('role','RoleController');

    //权限模块路由
    Route::get('permission/del','PermissionController@delAll');
    Route::resource('permission','PermissionController');
});

