<?php

namespace App\Acme\Helpers;

use App\Models\Game;
use Illuminate\Support\Str;
use Cache;

class SteamHelper{
    
    /**
     * Get channel streams
     *
     * @return mixed
     */
    static public function searchUserSummary($steam_ids)
    {
        $client = new \Zyberspace\SteamWebApi\Client(env('STEAM_KEY'));
        $steamUser = new \Zyberspace\SteamWebApi\Interfaces\ISteamUser($client);
        
        $response = $steamUser->GetPlayerSummariesV2(implode(',', $steam_ids));
        
        return $response;
        dd($response);
    }
    
    /**
     * Get channel streams
     *
     * @return mixed
     */
    static public function getFriends($steam_id)
    {
        $client = new \Zyberspace\SteamWebApi\Client(env('STEAM_KEY'));
        $steamUser = new \Zyberspace\SteamWebApi\Interfaces\ISteamUser($client);
        $response = $steamUser->GetFriendListV1($steam_id);
        
        $steam_ids = [];
        foreach($response->friendslist->friends as $arFriend)
        {
            $steam_ids[] = $arFriend->steamid;
        }
        
        foreach(array_chunk($steam_ids, 50) as $ids)
        {
            $response = self::searchUserSummary($ids);
            dd($response);
        }
    }
}