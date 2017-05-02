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

//social auth
Route::get('/social/{provider}', ['as' => 'social.auth', 'uses' => 'SocialController@login']);
Route::get('/social/{provider}/callback', 'SocialController@callback');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->get('users/{id}', 'App\Http\Controllers\UserController@show');
});