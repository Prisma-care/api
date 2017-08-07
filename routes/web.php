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

Route::post('/invite', [
    'uses' => 'Invite\UserController@store'
]);

Route::get('/password/reset/{token}', [
    'as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm'
]);
