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
Route::pattern('id', '[0-9]+');

Route::get('/', function () {
	\Auth::loginUsingId(2);
	// \Auth::logout();
    return view('welcome');
});


Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'role:admin|member'], function() {
	get('/test', 'DashBoardsController@index');
});


Route::group(['namespace' => 'Frontend'], function() {

});

Route::group(['prefix' => 'api'], function() {
    get('/user', 'API\AuthenticateController@index');
    Route::controller('auth', 'API\AuthenticateController', [
      'getLogin' => 'auth.login',
      'getRegister' => 'auth.register',
      'postLogin' => 'auth.login',
      'getLoginWithLinkedin' => 'auth.linkedin'
    ]);
});
