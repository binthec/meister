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

// 認証のルート定義
Route::get('/', 'Auth\AuthController@getlogin');
Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@authenticate');

Route::group(['middleware' => 'auth'], function() {
	//ログイン後のroute
	Route::match(['get', 'post'], '/dashboard', 'HomeController@dashboard');

	Route::get('/logout', 'Auth\AuthController@getLogout');

	// 登録のルート定義
	Route::match(['get', 'post'], '/auth/register', 'Auth\AuthController@register');

	//ユーザ管理
	Route::get('/user', 'UserController@index');
	Route::get('/user/profile/{id}', 'UserController@profile');
	Route::match(['get', 'post'], '/user/editProfile/{id}', 'UserController@editPofile');
	Route::match(['get', 'post'], '/user/editDate/{id}', 'UserController@editDate');
	Route::get('/user/reset/{id}', 'UserController@reset');
//	Route::get('/user/update/{userId?}', 'UserController@update');
//	
	//有給消化申請
	Route::match(['get', 'post'], '/use_request', 'UseRequestController@index');
	Route::match(['get', 'post'], '/use_request/add', 'UseRequestController@add');
	Route::match(['get', 'post'], '/use_request/edit/{id}', 'UseRequestController@edit');
	Route::get('/use_request/delete/{id}', 'UseRequestController@delete');

	//デバイス管理
	Route::match(['get', 'post'], '/device', 'DeviceController@index');
	Route::match(['get', 'post'], '/device/add', 'DeviceController@add');
	Route::match(['get', 'post'], '/device/edit/{id}', 'DeviceController@edit');
});



//管理者
//Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
//	Route::get('/', 'AdminController@index');
//});
