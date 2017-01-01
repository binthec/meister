<?php

// 認証のルート定義
Route::get('/', 'Auth\AuthController@getlogin');
Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@authenticate');

Route::group(['middleware' => 'auth'], function() {
	//ログイン後のroute
	Route::match(['get', 'post'], '/dashboard', 'HomeController@dashboard');

	Route::get('/logout', 'Auth\AuthController@getLogout');

	//ユーザ管理

	Route::get('/user/register', 'Auth\AuthController@getRegister');
	Route::post('/user/register', 'Auth\AuthController@postRegister');
	Route::get('/user/password/{id}/edit', 'UserController@passwordEdit');
	Route::put('/user/password/{id}', 'UserController@passwordUpdate');
	Route::get('/user/dateofentering/{id}/edit', 'UserController@dateEdit');
	Route::put('/user/dateofentering/{id}', 'UserController@dateUpdate');
	Route::get('/user/reset/{id}', 'UserController@reset');
	Route::resource('/user', 'UserController');

	//有給消化登録
//	Route::match(['get', 'post'], '/use_request/edit/{id}', 'UseRequestController@edit');
//	Route::get('/use_request/delete/{id}', 'UseRequestController@delete');
	Route::resource('/use_request', 'UseRequestController', ['except' => 'show']);

	//デバイス管理
	Route::resource('/device', 'DeviceController');
});



//管理者
//Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
//	Route::get('/', 'AdminController@index');
//});
