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
Route::resource('countries', 'CountryController', ['only' => [
    'index', 'show'
]]);//apiHandler

/**
 * Users
 */
/**
 * @api {get} /users/me Get own User
 * @apiName own
 * @apiGroup User
 * @apiPermission Authenticated User
 */
Route::get('/users/me', 'UserController@me')->middleware('jwt.auth');
//Route::get('/users/search', 'UserController@search');

/**
 * @api {get} /users List all users
 * @apiName all
 * @apiGroup User
 * @apiPermission none
 */
Route::get('/users/', 'UserController@index');//apiHandler

/**
 * @api {get} /users/:id Read data of a User
 * @apiName show
 * @apiGroup User
 * @apiPermission none
 * @apiDescription Realations: country, team, game, teams, fights, createdFights, judgeOfFights, commentatorOfFights, canceledFights
 * 
 * @apiParam {Number} id Users unique ID.
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 OK
{
  "id": 512,
  "role_id": 2,
  "name": "VLADISLAV",
  "email": "v-ilchenko@yandex.ru",
  "avatar": "avatars/Z4t3tYoGt4vHi7kID7t72SXrrkiQpsezebFd4FpX.png",
  "created_at": "2017-06-21 11:40:52",
  "updated_at": "2017-06-21 11:51:11",
  "type": "player",
  "second_name": null,
  "last_name": null,
  "nickname": "v-ilchenko@yandex.ru",
  "phone": null,
  "active": 1,
  "confirmed": 1,
  "rating": null,
  "team_wins": null,
  "person_wins": null,
  "min_sponsor_fee": null,
  "credit_rating": null,
  "notify": "y",
  "overlay": null,
  "description": null,
  "country_id": 643,
  "balance": 0
}
 */
Route::get('/users/{id}', 'UserController@show');//apiHandler
Route::get('/users/{id}/teams', 'UserController@teams');//apiHandler
Route::get('/users/{id}/fights', 'UserController@fights');//apiHandler

/**
 *Genres
 */
Route::resource('genres', 'GenreController', ['only' => [
    'index', 'show'
]]);

/**
 * Games
 */
Route::resource('games', 'GameController', ['only' => [
    'index', 'show'
]]);//apiHandler


/**
 * Teams
 */
Route::get('/teams/{param}', 'TeamController@show');//apiHandler
Route::get('/teams', 'TeamController@index');//apiHandler


/**
 * Twitch & streams
 */
/**
 * @api {get} /twitch Featured streams
 * @apiName featured
 * @apiGroup Twitch
 * @apiPermission guest:api
 * @apiDescription Get list of featured streams.
 * 
 */
Route::get('/twitch/', '\App\Acme\Helpers\TwitchHelper@getFeaturedStreams');
/**
 * @api {get} /twitch/search/:game Search by the game
 * @apiName password-email
 * @apiGroup Twitch
 * @apiPermission guest:api
 * @apiDescription Search streams by the name of the game
 * 
 * @apiParam {String} game Name of the game
 */
Route::get('/twitch/search/{game}', '\App\Acme\Helpers\TwitchHelper@searchStreamsByGame');
Route::get('/twitch/{channel}', '\App\Acme\Helpers\TwitchHelper@channelShow');

/**
 * Figths
 */
Route::resource('fights', 'FightController', ['only' => [
    'index', 'show'
]]);

/**
 * Login & register
 */
Route::group(['middleware' => 'guest:api'], function () {
    
    /**
     * @api {post} /register Register
     * @apiName register
     * @apiGroup OAuth2
     * @apiPermission guest:api
     * @apiDescription Register Users using their email, password, name. After user'll recieve email with confirmation link to verify the email.
     * 
     * @apiParam {String} email user email
     * @apiParam {String} password user password
     * @apiParam {String} name user name
     */
    Route::post('/register', 'Auth\AuthController@register');
    
    /**
     * @api {post} /login Login (Client Credentials)
     * @apiName login
     * @apiGroup OAuth2
     * @apiPermission guest:api
     * @apiDescription Login Users using their email and password.
     * 
     * @apiParam {String} email user email
     * @apiParam {String} password user password
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
{
  "token_type": "Bearer",
  "expires_in": 315360000,
  "token": "eyJ0eXAiOiJKV1QiLCJhbG..."
}
     */
    Route::post('/login', 'Auth\AuthController@login');
    
    /**
     * @api {post} /password/email Reset password link
     * @apiName password-email
     * @apiGroup OAuth2
     * @apiPermission guest:api
     * @apiDescription Link for reseting of password sends to email.
     * 
     * @apiParam {String} email user email
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
{
    "message": "We have e-mailed your password reset link!"
}
     *
     * @apiErrorExample {json} Error-Response:
     * HTTP/1.1 422 Unprocessable Entity
{
    "email": "We can't find a user with that e-mail address."
}
     */
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    
    /**
     * @api {post} /password/reset Reset password
     * @apiName password-reset
     * @apiGroup OAuth2
     * @apiPermission guest:api
     * @apiDescription Reset password using token from email.
     * 
     * @apiParam {String} email user email
     * @apiParam {String} password new user password
     * @apiParam {String} password_confirmation new user password repeat
     * @apiParam {String} token token from email
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
{
    "message": "Your password has been reset!"
}
     *
     * @apiErrorExample {json} Error-Response:
     * HTTP/1.1 422 Unprocessable Entity
{
    "password": [
        "The password confirmation does not match.",
        "The password must be at least 6 characters.",
        "We can't find a user with that e-mail address.",
        "This password reset token is invalid."
    ]
}
     */
    Route::post('/password/reset', 'Auth\ResetPasswordController@postReset');
    Route::get('/email/verify/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'Auth\AuthController@verify'
    ]);
    
    /**
     * @api {get} /social/:provider Login
     * @apiName login
     * @apiGroup SocialAuth
     * @apiPermission guest:api
     * @apiDescription Login & register using social accounts. Redirect to social site.
     * 
     * @apiParam {String} provider social provider: vkontakte|google|facebook|twitter|steam|twitch
     */
    Route::get('/social/{provider}', 'SocialController@login')->name('social.auth');
    /**
     * @api {get} /social/:provider/callback Login callback
     * @apiName login-callback
     * @apiGroup SocialAuth
     * @apiPermission guest:api
     * @apiDescription Callback from social site. Create user from social account.
     * 
     * @apiParam {String} provider social provider: vkontakte|google|facebook|twitter|steam|twitch
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
{
  "token_type": "Bearer",
  "expires_in": 315360000,
  "token": "eyJ0eXAiOiJKV1QiLCJhbG..."
}
     */
    Route::get('/social/{provider}/callback', 'SocialController@callback');
});

Route::group(['middleware' => 'jwt.auth'], function () {
    
    /**
     * Users & auth
     */    
        
    /**
     * @api {post} /refresh Refresh
     * @apiName refresh
     * @apiGroup OAuth2
     * @apiPermission Authenticated User
     * @apiDescription Refresh access token based on refreshToken http cookie.
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
{
  "token_type": "Bearer",
  "expires_in": 315360000,
  "token": "eyJ0eXAiOiJKV1QiLCJhbG..."
}
     */
    Route::post('/refresh', 'Auth\AuthController@refresh');
    
    
    /**
     * @api {post} /logout Logout
     * @apiName logout
     * @apiGroup OAuth2
     * @apiPermission Authenticated User
     * @apiDescription User Logout. (Revoking Access Token)
     * 
     * @apiSuccessExample {json} Success-Response:
     *HTTP/1.1 202 Accepted
{
  "message": "Token revoked successfully."
}
     */
    Route::post('/logout', 'Auth\AuthController@logout');
    
    
    Route::post('/users', 'UserController@update');
    Route::post('/users/avatar', 'UserController@avatar');
    Route::post('/users/overlay', 'UserController@overlay');
    Route::get('/users/{id}/teams/invites', 'UserController@invitesToTeam');
    Route::get('/users/{userId}/teams/{teamId}', 'UserController@getTeamById');
    

    
    /**
     * Teams
     */
    Route::get('/teams/{id}/users', 'TeamController@users');
    Route::get('/teams/{teamId}/users/{userId}', 'TeamController@userById');
    Route::put('/teams/{teamId}/users/{userId}', 'TeamController@inviteUser');
    Route::post('/teams/{id}/toInvestor', 'TeamController@linkToInvestor');
    Route::resource('teams', 'TeamController', ['only' => [
        'store', 'update', 'destroy', 'edit'
    ]]);//apiHandler
    Route::post('/teams/logo', 'TeamController@logo');
    Route::post('/teams/overlay', 'TeamController@overlay');
    Route::post('/teams/{id}', 'TeamController@update');    
    
    /**
     * Fights
     */
    Route::resource('fights', 'FightController', ['only' => [
        'store', 'update', 'destroy', 'edit'
    ]]);
    
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