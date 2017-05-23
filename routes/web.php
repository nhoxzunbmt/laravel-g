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
})->name('home');

//Register and auth by email
Auth::routes();

//social auth
Route::get('/social/{provider}', 'SocialController@login')->name('social.auth');
Route::get('/social/{provider}/callback', 'SocialController@callback');

//Admin routes
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

//Games
Route::get('/games', 'GameController@index')->name('games');
Route::get('/games/{id}', 'GameController@show')->name('game');
Route::get('/games/search/{q}', 'GameController@search');


//Fights
/*Route::get('/fights', 'FightController@index')->name('fights');
Route::get('/fights/{id}', 'FightController@show')->name('fight');
Route::get('/fights/{id}', 'FightController@show')->name('fight');
Route::get('/games/search/{q}', 'GameController@search');*/

Route::resource('fights','FightController');

//Api routes
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->get('users/{id}', 'App\Http\Controllers\UserController@show');
});

//Route::get('/genre/import', 'GanreController@importByGiantbomb');
//Route::get('/game/import', 'GameController@importByTwitchGiantbomb');
Route::get('/twitch/search/{game}', '\App\Acme\Helpers\TwitchHelper@searchStreamsByGame');