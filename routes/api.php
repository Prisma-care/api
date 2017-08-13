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

Route::group(['prefix' => 'v1'], function () {
    Route::resource('user', 'UserController', [
        'only' => ['store', 'show', 'update']
    ]);

    Route::post('user/signin', [
       'as' => 'signin', 'uses' => 'Auth\LoginController@signin'
    ]);
    Route::post('user/signout', [
       'as' => 'signout', 'uses' => 'Auth\LogoutController@signout'
    ]);

    Route::resource('patient', 'PatientController', [
        'only' => ['store', 'show']
    ]);

    Route::resource('patient.album', 'AlbumController', [
        'except' => ['edit', 'create']
    ]);

    Route::resource('patient.story', 'StoryController', [
        'except' => ['index', 'edit', 'create']
    ]);

    Route::resource('patient.story.asset', 'StoryAssetController', [
       'only' => ['store', 'show', 'update']
    ]);

    Route::match(['link'], 'patient/{patientId}/connection', 'ConnectionController@connect');
    Route::match(['unlink'], 'patient/{patientId}/connection', 'ConnectionController@disconnect');

    Route::post('invite', function(){
        return response([], 204);
    });
});
