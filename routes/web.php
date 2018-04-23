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

// Authentication
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

// nem-apps Resources
Route::resource("users", "UsersController");
Route::resource("addresses", "AddressesController");
Route::resource("deposits", "DepositsController");
Route::resource("withdrawals", "WithdrawalsController");
