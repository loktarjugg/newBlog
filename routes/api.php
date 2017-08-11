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
    Route::post('upload','UploadController@upload')
        ->middleware(['auth:api','admin'])->name('upload');
    Route::resource('articles', 'ArticleController'); //articles
    Route::resource('tags','TagController'); //tags
    Route::resource('shares','ShareController');
    Route::get('settings','SetController@index');
    Route::put('settings','SetController@update')->middleware(['auth:api','admin']);
});
