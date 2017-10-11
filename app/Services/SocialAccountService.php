<?php

namespace App\Services;

use App\Models\UserSocialAccount;
use App\User;
use Illuminate\Http\Request;
use App\Acme\Helpers\TwitchHelper;

class SocialAccountService
{
    public function createOrGetUser($providerObj, $providerName)
    {
        $providerUser = $providerObj->user();

        $account = UserSocialAccount::whereProvider($providerName)
                        ->whereProviderUserId($providerUser->getId())
                            ->first();              
        if ($account) 
        {
            return $account->user;
        } 
        else 
        {
            $user = [];
            
            $account = new UserSocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $providerName
            ]);

            $nickname = $providerUser->getNickname();
            
            if(!empty($nickname))
                $user = User::where('nickname', $nickname)->first();

            if (!$user) 
            {
                $user = User::createBySocialProvider($providerUser);
                
                if($providerName=='twitch')
                {
                    $streams = [];
                    $data = TwitchHelper::getVideosByUsername($nickname);
                    foreach($data as $stream)
                    {
                        $streams[] = $stream['url'];
                    }
                    
                    $user->update([
                        'streams' => $streams
                    ]);
                }
            }

            $account->user()->associate($user);
            $account->save();

            return $user;
        }
    }
}