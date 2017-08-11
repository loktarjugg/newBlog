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

Route::get('/','HomeController@index');

Route::get('/login','Auth\LoginController@showLoginForm')->name('admin_login_form');
Route::post('/login','Auth\LoginController@login')->name('admin_login');
Route::get('logout','Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin/{subs?}', 'HomeController@admin')
    ->middleware(['auth:web','admin'])
    ->where(['subs' => '.*']);
