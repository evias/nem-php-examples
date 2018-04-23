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

Route::get('getting-started', function () {
    return view('welcome');
});

Route::get('nem-sandbox', function () {
    return view('welcome');
});

Route::get('login', '\App\Http\Controllers\Auth\LoginController@showLoginForm')->name("login");
Route::post('login', '\App\Http\Controllers\Auth\LoginController@login');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
