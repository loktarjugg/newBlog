<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' =>'v1',
    'namespace' => 'Api\V1',
],function () {
    Route::post('login' , 'LoginController@login')->middleware('cors')->name('api.login');
    Route::post('register' , 'RegisterController@register')->middleware('cors')->name('api.register');
    Route::post('upload','UploadController@upload')->name('upload');
//        ->middleware(['auth:api','admin'])->name('upload');
    Route::get('token/qiniu','UploadController@token')->middleware('cors')->name('qiniu.token');
    Route::get('articles/{id}/replies' , 'ArticleController@replies')->name('articles.replies');
    Route::post('articles/{id}/vote' , 'ArticleController@vote')->middleware(['auth:api']);
    Route::resource('articles', 'ArticleController'); //articles
    Route::resource('tags','TagController'); //tags
    Route::resource('shares','ShareController');
    Route::get('settings','SetController@index')->name('settings.index');
    Route::put('settings','SetController@update')->middleware(['auth:api','admin'])->name('settings.put');
    Route::resource('replies','ReplyController');
});
