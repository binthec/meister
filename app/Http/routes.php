<?php

Route::group(['middleware' => 'guest'], function() {
	Route::get('/', 'Auth\AuthController@getlogin');
	Route::get('/login', 'Auth\AuthController@getLogin');
	Route::post('/login', 'Auth\AuthController@authenticate');
});

Route::group(['middleware' => 'auth'], function() {
	//ログイン後のroute
	Route::get('/dashboard', 'HomeController@dashboard');
	Route::get('/logout', 'Auth\AuthController@getLogout');

	//ユーザ管理
	Route::get('/user/register', 'Auth\AuthController@getRegister');
	Route::post('/user/register', 'Auth\AuthController@postRegister');
	Route::get('/user/password/{id}/edit', 'UserController@passwordEdit');
	Route::put('/user/password/{id}', 'UserController@passwordUpdate');
	Route::get('/user/dateofentering/{id}/edit', 'UserController@dateEdit');
	Route::put('/user/dateofentering/{id}', 'UserController@dateUpdate');
	Route::resource('/user', 'UserController');

	//有給消化登録
	Route::resource('/vacation', 'VacationController', ['except' => 'show']);

	//デバイス管理
	Route::resource('/device', 'DeviceController');

	//ライセンス管理
	Route::resource('/license', 'LicenseController', ['except' => 'show']);

	//ベンダー管理
	Route::resource('/license/maker', 'MakerController', ['except' => 'show']);

	//隠しコマンド。登録してる分全てリセット出来る機能。危ないので隠しときます。
	//Route::get('/user/reset/{id}', 'UserController@reset');
});
