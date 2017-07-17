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
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('patient', 'ProfileController', [
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
});
