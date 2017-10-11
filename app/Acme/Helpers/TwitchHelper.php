<?php

namespace App\Acme\Helpers;

use App\Models\Game;
use Illuminate\Support\Str;
use Cache;
use Illuminate\Http\Request;

class TwitchHelper{
    
    /**
     * Get channel streams
     *
     * @return mixed
     */
    static public function searchStreamsByGame($game_id)
    {
        $cache_key = 'searchStreamsByGames'.$game_id;
        
        if (Cache::has($cache_key)){
            return Cache::get($cache_key);
        } else {
        
            $game = Game::findOrFail($game_id);
        
            $twitchClient = new \TwitchApi\TwitchApi([
                'client_id' => env('TWITCH_API_CLIENT_ID')
            ]);            
            $channels = [];
            $limit = 10;
            $offset = 0;
            $responseTwitch = $twitchClient->searchStreams(urlencode($game['title']), $limit, $offset, true);
            $total = intval($responseTwitch['_total']);
            $streams = $responseTwitch["streams"];
            
            Cache::put($cache_key, $streams, 60);
            
            return $streams;
        }
    }
    
    public function channelShow($channel)
    {
        $cache_key = 'twitchChannelShow'.Str::slug($channel);
        
        if (Cache::has($cache_key)){
            $channel = Cache::get($cache_key);
        } else {

            $twitchClient = new \TwitchApi\TwitchApi([
                'client_id' => env('TWITCH_API_CLIENT_ID')
            ]);
            $twitchClient->setApiVersion(4);
            $channel = $twitchClient->getChannel($channel);
            
            Cache::put($cache_key, $channel, 60);
        }
        
        return $channel;
    }
    
    public function getFeaturedStreams(Request $request)
    {
        $cache_key = 'twitchgetFeaturedStreams';
        
        if (Cache::has($cache_key)){
            $streams = Cache::get($cache_key);
        } else {
            $twitchClient = new \TwitchApi\TwitchApi([
                'client_id' => env('TWITCH_API_CLIENT_ID')
            ]); 
            
            $limit = 100;
            $offset = 0;
            if($request->has('limit')) 
            {
                $limit = intval($request->get('limit'));
            } 
            if($request->has('offset')) 
            {
                $offset = intval($request->get('offset'));
            }
    
            $responseTwitch = $twitchClient->getFeaturedStreams($limit, $offset);
            $streams = $responseTwitch["featured"];
            
            Cache::put($cache_key, $streams, 10);
        }

        return $streams;
    }
    
    public function getVideosByUsername($channel)
    {
        $twitchClient = new \TwitchApi\TwitchApi([
            'client_id' => env('TWITCH_API_CLIENT_ID')
        ]);
        $twitchClient->setApiVersion(4);
        
        $limit = 50;
        $offset = 0;
        $responseTwitch = $twitchClient->getChannelVideos($channel, $limit, $offset);
        
        return $responseTwitch['videos'];
    }
}