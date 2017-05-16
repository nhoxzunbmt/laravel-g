<?php

namespace App\Acme\Helpers;

use App\Models\Game;

class TwitchHelper{
    
    /**
     * Get channel streams
     *
     * @return mixed
     */
    static public function searchStreamsByGame($game)
    {
        $twitchClient = new \TwitchApi\TwitchApi([
            'client_id' => env('TWITCH_API_CLIENT_ID'),
        ]);
        
        $channels = [];
        $limit = 10;
        $offset = 0;
        $responseTwitch = $twitchClient->searchStreams(urlencode($game), $limit, $offset);
        $total = intval($responseTwitch['_total']);
        $streams = $responseTwitch["streams"];
        
        foreach($streams as $arStream)
        {
            $channels[] = $arStream['channel']['name'];
        }
        
        
        //return view('home', [ 'channels' => $channels]);
        return $channels;
    }
}