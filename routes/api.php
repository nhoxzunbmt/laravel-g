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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/games/popular', 'GameController@popular');
Route::get('/users', 'UserController@index');


Route::post('/register', [
    'uses' => 'Auth\AuthController@register',
]);

Route::post('/signin', [
    'uses' => 'Auth\AuthController@signin',
]);

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/user', function (Request $request) {
        $data = [];
        $data['name'] = $request->user()->name;
        $data['email'] = $request->user()->email;
        return response()->json([
            'data' => $data,
        ]);
    });
});