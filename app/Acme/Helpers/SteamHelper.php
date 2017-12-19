<?php

namespace App\Acme\Helpers;

use App\Models\Game;
use App\Models\UserSocialAccount;
use App\User;
use Illuminate\Support\Str;
use Cache;
use File;
use Image;
use App\Acme\Helpers\OpenDotaHelper;

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
    }
    
    /**
     * Get channel streams
     *
     * @return mixed
     */
    static public function getFriends($steam_id)
    {        
        $result = [];
        $client = new \Zyberspace\SteamWebApi\Client(env('STEAM_KEY'));
        $steamUser = new \Zyberspace\SteamWebApi\Interfaces\ISteamUser($client);
        $response = $steamUser->GetFriendListV1($steam_id, 'friend');

        $steam_ids = [];
        if(count($response->friendslist->friends)>0)
        {
            foreach($response->friendslist->friends as $arFriend)
            {
                $steam_ids[] = $arFriend->steamid;
            }
        }
        
        $data = [];
        if(count($steam_ids)>0)
        {
            foreach(array_chunk($steam_ids, 50) as $ids)
            {
                $data = self::searchUserSummary($ids);
                
                //remove private profiles
                $dataModified = [];
                foreach($data->response->players as $player)
                {
                    if(in_array($player->communityvisibilitystate, [3,4,5]))
                    {
                        $dataModified[] = $player;
                    }
                }
                
                $result = array_merge($result, $dataModified);
            }
        }
        
        return $result;
    }
    
    static public function importUsersFromFriends()
    {
        $limit = 100;
        $data = [];
        
        $socialAccounts = UserSocialAccount::where("provider", "steam");
        $count = $socialAccounts->count();
        $provider_ids = $socialAccounts->get()->pluck("provider_user_id")->toArray();
        //$provider_ids = ['76561197960435530'];
        
        self::krakenGetUsers($provider_ids, $count, $limit, $data);
        
        foreach($data as $steamAccount)
        {
            if(!in_array($steamAccount->steamid, $provider_ids))
            {
                $account = new UserSocialAccount([
                    'provider_user_id' => $steamAccount->steamid,
                    'provider' => 'steam'
                ]);
                $user = self::createUser($steamAccount);
                $account->user()->associate($user);
                $account->save();
                
                $provider_ids[] = $steamAccount->steamid;
            }
        }
    }
    
    static public function krakenGetUsers($steam_ids, $count, $limit, &$data)
    {
        if($count>=$limit)   //exit from recursion
        {
            return;
        }
        
        foreach($steam_ids as $steam_id)
        {
            $steams = self::getFriends((string)$steam_id);
            $data = array_merge($data, $steams);
            $count+= count($steams);
            
            $steam_ids_added = [];
            foreach($steams as $steam)
            {
                $steam_ids_added[] = $steam->steamid;
            }
            
            self::krakenGetUsers($steam_ids_added, $count, $limit, $data);
        }
    }
    
    static function createUser($steamAccount)
    {
        $nickname = $steamAccount->personaname;
        $site = env('APP_URL', "sparta.games");
        $site = str_replace(["http://", "https://"], "", $site);
        $email = $nickname."@".$site;
        $confirmed = 0;
        $active = 0;
            
        $avatar = $steamAccount->avatarfull;
        if(!empty($avatar))
        {
            $extension = strtolower(File::extension(basename($avatar)));
            if(in_array($extension, ["jpg", "jpeg", "png"]) && !empty($avatar) && @getimagesize($avatar))
            {
                $image = 'avatars/'.basename($avatar);
                $image = str_replace(array("%", "+", ":"), "", $image);
                Image::make($avatar)->save(public_path("storage/".$image));
                $avatar = $image;
            }
        }else{
            $avatar = null;
        }
                              
        $data = [
            'email' => $email,
            'nickname' => $nickname."_".$steamAccount->steamid,
            'name' => isset($steamAccount->realname) ? $steamAccount->realname : $nickname,
            'password' => str_random(10),
            'avatar' => $avatar,
            'confirmed' => $confirmed,
            'active' => $active          
        ];                               
        
        return User::create($data);
    }
    
    public static function convertSteamid64ToSteamid32($id)
    {
        $result = substr($id, 3) - 61197960265728;
        return (string) $result;
    }
    
    public static function getDota2Player($steam_id32)
    {
        if(strlen((string)$steam_id32)>14)
            $steam_id32 = self::convertSteamid64ToSteamid32($steam_id32);
        
        $openDota = new OpenDotaHelper(['returnJson' => true], []);
        return $openDota->getPlayer($steam_id32);
    }
    
    public static function getDota2PlayerWinLoss($steam_id32)
    {
        if(strlen((string)$steam_id32)>14)
            $steam_id32 = self::convertSteamid64ToSteamid32($steam_id32);
        
        $openDota = new OpenDotaHelper(['returnJson' => true], []);
        return $openDota->getPlayerWinLoss($steam_id32);
    } 
    
    public static function getDota2PlayerTotal($steam_id32)
    {
        if(strlen((string)$steam_id32)>14)
            $steam_id32 = self::convertSteamid64ToSteamid32($steam_id32);
        
        $openDota = new OpenDotaHelper(['returnJson' => true], []);
        return $openDota->getPlayerTotal($steam_id32);
    }  
}