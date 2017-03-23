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
    return view('start');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/city/{name}', 'CityController@index');
Route::post('/city/create/{name}', 'CityController@create');

Route::get('/contact/{user_id}', 'ContactController@get');
Route::get('/contact/create', 'ContactController@create');

Route::get('/user/{id}', 'UserController@index');
Route::post('/user/register', 'UserController@create');
Route::get('/user/update', 'UserController@update');
