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

Route::get('/download', function () {
    return view('download');
});

Route::post('/invite', [
    'uses' => 'Invite\UserController@store'
]);

Route::get('/password/reset/{token}', [
    'as' => 'password.reset', 'uses' => 'Auth\InviteSetPasswordController@showResetForm'
]);

Route::post('password/reset', [
    'as' => 'password.request', 'uses' => 'Auth\ResetPasswordController@reset'
]);
// TO DO : guard this in auth middleware later
Route::get('/goto-download', [
    'as' => 'goto.download', 'uses' => 'Auth\InviteSetPasswordController@goToDownload'
]);

Route::get('/login', [
    'as' => 'login', 'uses' => 'Admin\LoginController@showLoginForm'
]);

Route::post('/login', [
    'uses' => 'Admin\LoginController@login'
]);

Route::get('/register', [
    'as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm'
]);

Route::post('/register', [
     'uses' => 'Auth\RegisterController@register'
]);


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
