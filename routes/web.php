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

//Admin routes
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

//social auth
Route::get('/social/{provider}', 'SocialController@login')->name('social.auth');
Route::get('/social/{provider}/callback', 'SocialController@callback');

Route::get('/password/reset/{token?}', function () {
    return view('index');
})->name('password.reset');

Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)');


/*
//Register and auth by email
Auth::routes();

//Games
Route::get('/games', 'GameController@index')->name('games');
Route::get('/games/{id}', 'GameController@show')->name('game');
Route::get('/games/search/{q}', 'GameController@search');

//Fights
Route::get('/fights', 'FightController@index')->name('fights');
Route::get('/fights/create', 'FightController@create');*/
/*Route::get('/fights/{id}', 'FightController@show')->name('fight');
Route::get('/fights/{id}', 'FightController@show')->name('fight');
Route::get('/games/search/{q}', 'GameController@search');*/

//Route::resource('fights','FightController');

//Api routes
/*$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->get('users/{id}', 'App\Http\Controllers\UserController@show');
});*/

//Route::get('/genre/import', 'GanreController@importByGiantbomb');
//Route::get('/game/import', 'GameController@importByTwitchGiantbomb');
/*Route::get('/twitch/search/{game}', '\App\Acme\Helpers\TwitchHelper@searchStreamsByGame');
Route::get('/twitch/{channel}', '\App\Acme\Helpers\TwitchHelper@channelShow');
Route::get('/steam/search/{steam_id}', '\App\Acme\Helpers\SteamHelper@getFriends');*/