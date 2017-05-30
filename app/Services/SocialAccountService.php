<?php

namespace App\Services;

use App\Models\UserSocialAccount;
use App\User;

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

            $email = $providerUser->getEmail();
            
            if(!empty($email))
                $user = User::whereEmail($email)->first();

            if (!$user) 
            {
                $user = User::createBySocialProvider($providerUser);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;
        }
    }
}