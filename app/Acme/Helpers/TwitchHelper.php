<?php

namespace App\Acme\Helpers;

use App\Models\Game;
use Illuminate\Support\Str;
use Cache;

class TwitchHelper{
    
    /**
     * Get channel streams
     *
     * @return mixed
     */
    static public function searchStreamsByGame($game)
    {
        $cache_key = 'searchStreamsByGames'.Str::slug($game);
        
        if (Cache::has($cache_key)){
            return Cache::get($cache_key);
        } else {
            
            $twitchClient = new \TwitchApi\TwitchApi([
                'client_id' => env('TWITCH_API_CLIENT_ID'),
            ]);
            $channels = [];
            $limit = 10;
            $offset = 0;
            $responseTwitch = $twitchClient->searchStreams(urlencode($game), $limit, $offset, true);
            $total = intval($responseTwitch['_total']);
            $streams = $responseTwitch["streams"];
            
            Cache::put($cache_key, $streams, 60);
            
            /*foreach($streams as $arStream)
            {
                $channels[] = $arStream['channel']['name'];
            }*/
            
            //return view('home', [ 'channels' => $channels]);
            //return $channels;
                
            return $streams;
        }
    }
}