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
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('start');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/city/{name}', 'CityController@index');
Route::post('/city/create/{name}', 'CityController@create');

Route::get('/contact/{user_id}', 'ContactController@get');
Route::get('/contact/create', 'ContactController@create');

Route::get('/user/{id}', 'UserController@index');
Route::post('/user/register', 'UserController@create');
Route::get('/user/{id}/update', 'UserController@update');
Route::post('/user/{id}/update', 'UserController@update');
Route::get('/user/{id}/contact', 'UserController@contact');

Route::get('/statuses', 'DataController@statuses');
Route::get('/status/{status}/change/', 'DataController@change');
Route::get('/status/pay/{amount}', 'DataController@pay');
