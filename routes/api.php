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

/**
 * Countries
 */
Route::get('/countries', 'CountryController@index');
//Route::get('/user/search', 'UserController@search');


/**
 *Genres
 */
Route::resource('genres', 'GenreController', ['only' => [
    'index', 'show'
]]);

/**
 * Games
 */
Route::get('/games/popular', 'GameController@popular');
Route::resource('games', 'GameController', ['only' => [
    'index', 'show'
]]);


/**
 * Teams
 */
Route::get('/teams/{param}', 'TeamController@show');
Route::resource('teams', 'TeamController', ['only' => [
    'index'/*, 'show'*/
]]);

/**
 * Twitch & streams
 */
Route::get('/twitch/search/{game}', '\App\Acme\Helpers\TwitchHelper@searchStreamsByGame');
Route::get('/twitch/{channel}', '\App\Acme\Helpers\TwitchHelper@channelShow');

/**
 * Login & register
 */
Route::group(['middleware' => 'guest:api'], function () {
    
    Route::post('/register', 'Auth\AuthController@register');
    Route::post('/login', 'Auth\AuthController@signin');
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'Auth\ResetPasswordController@postReset');
    Route::get('/social/{provider}/callback', 'SocialController@callback');
    Route::get('/auth/verify/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'Auth\AuthController@verify'
    ]);
});

Route::group(['middleware' => 'jwt.auth'], function () {
    
    Route::get('/user', function (Request $request) {
        $data = App\User::getApiUserData($request->user());
        return response()->json($data);
    });
    
    Route::post('/user', 'UserController@update');
    Route::post('/user/avatar', 'UserController@avatar');
    Route::post('/user/overlay', 'UserController@overlay');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::get('/user/{id}/teams', 'UserController@teams');
    
    /**
     * Teams
     */
    Route::resource('teams', 'TeamController', ['only' => [
        'store', 'update', 'destroy', 'edit'
    ]]);
    Route::post('/teams/logo', 'TeamController@logo');
    Route::post('/teams/overlay', 'TeamController@overlay');
    Route::post('/teams/{id}', 'TeamController@update');

    /**
     * Friends
     */
    Route::post('/friends/befriend', 'FriendController@befriend');
    Route::post('/friends/unfriend', 'FriendController@unfriend');
    Route::post('/friends/acceptFriendRequest', 'FriendController@acceptFriendRequest');
    Route::get('/friends/getPendingOutFriends', 'FriendController@getPendingOutFriends');
    Route::get('/friends/getPendingInFriends', 'FriendController@getPendingInFriends');    
    Route::get('/friends/getFriends', 'FriendController@getFriends');
    Route::get('/friends/searchPotential', 'FriendController@searchPotentialFriends');
    
});