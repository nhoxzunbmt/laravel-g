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


/**
 *Genres
 */
Route::get('/genres', 'GenreController@index');
Route::get('/genres/{id}', 'GenreController@show');

/**
 * Games
 */
Route::get('/games', 'GameController@index');
Route::get('/games/popular', 'GameController@popular');
Route::get('/games/{id}', 'GameController@show')->name('game');

/**
 * Twitch & streams
 */
Route::get('/twitch/search/{game}', '\App\Acme\Helpers\TwitchHelper@searchStreamsByGame');
Route::get('/twitch/{channel}', '\App\Acme\Helpers\TwitchHelper@channelShow');

/**
 * Login & register
 */
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
        $data['avatar'] = $request->user()->avatar;
        return response()->json([
            'data' => $data,
        ]);
    });
});