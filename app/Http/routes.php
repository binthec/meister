<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

//Route::get('/', function () {
//    return view('welcome');
//});
//Route::get('/', 'Auth\AuthController@getLogin');
//Route::get('/home', 'HomeController@dashboard');
//ログイン後のroute
Route::get('/dashboard', 'HomeController@dashboard');
Route::post('/dashboard', 'HomeController@dashboard');

// 認証のルート定義
Route::get('/', 'Auth\AuthController@getLogin');

//Route::post('auth/login', 'Auth\AuthController@postLogin');
//Route::get('/login', 'Auth\AuthController@login');
//Route::post('/login', 'Auth\AuthController@login');
Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@postLogin');

Route::get('/logout', 'Auth\AuthController@getLogout');

// 登録のルート定義
Route::get('/auth/register', 'Auth\AuthController@register');
Route::post('/auth/register', 'Auth\AuthController@register');

//ユーザ管理
Route::get('/user', 'UserController@index');
Route::post('/user/edit/{id}', 'UserController@edit');

Route::get('/user/edit/{id}', 'UserController@edit');
Route::get('/user/edit/', 'UserController@edit');

Route::get('/user/reset/{id}', 'UserController@reset');

//有給消化申請
Route::get('/user/use_request', 'UseRequestController@useRequest');
Route::post('/user/use_request', 'UseRequestController@useRequest');
Route::get('/user/request_edit/{id}', 'UseRequestController@requestEdit');
Route::post('/user/request_edit', 'UseRequestController@requestEdit');

//登録済み有給一覧
Route::get('/user/used_list', 'UserController@usedList');
