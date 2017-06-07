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
Route::get('/games/{id}', 'GameController@show');

/**
 * Twitch & streams
 */
Route::get('/twitch/search/{game}', '\App\Acme\Helpers\TwitchHelper@searchStreamsByGame');
Route::get('/twitch/{channel}', '\App\Acme\Helpers\TwitchHelper@channelShow');

/**
 * Login & register
 */
//Route::group(['middleware' => 'guest:api'], function () {
    Route::post('/register', 'Auth\AuthController@register');
    Route::post('/login', 'Auth\AuthController@signin');
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'Auth\ResetPasswordController@postReset');
    Route::get('/social/{provider}/callback', 'SocialController@callback');
//});

Route::group(['middleware' => 'jwt.auth'], function () {
    
    Route::get('/user', function (Request $request) {
        $data = App\User::getApiUserData($request->user());
        return response()->json($data);
    });
    
    Route::post('/user', 'UserController@store');
});