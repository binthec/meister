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

////ユーザ管理
Route::get('/user', 'UserController@index');
Route::match(['get', 'post'], '/user/edit/{id}', 'UserController@edit');
Route::get('/user/reset/{id}', 'UserController@reset');
Route::get('/user/update', 'UserController@update');

//有給消化申請
Route::match(['get', 'post'], '/use_request', 'UseRequestController@index');
Route::match(['get', 'post'], '/use_request/add', 'UseRequestController@add');
Route::match(['get', 'post'], '/use_request/edit/{id}', 'UseRequestController@edit');
Route::get('/use_request/delete/{id}', 'UseRequestController@delete');

//登録済み有給一覧
//Route::get('/user/used_list', 'UserController@usedList');


//管理者
//Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
//	Route::get('/', 'AdminController@index');
//});
