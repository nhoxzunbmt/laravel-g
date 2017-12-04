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
            
            $providerUser->contacts = [];
            if($providerName=="google")
            {
                // Установим токен в  Google API PHP Client
                $google_client_token = [
                    'access_token' => $providerUser->token
                ];
            
                $client = new Google_Client();
                $client->setApplicationName(env("GOOGLE_APP_NAME"));
                $client->setDeveloperKey(env('GOOGLE_SERVER_KEY'));
                $client->setAccessToken(json_encode($google_client_token));
                // Запросим контакты пользователя
                $service = new Google_Service_People($client);
            
                $optParams = array('requestMask.includeField' => 'person.phone_numbers,person.names,person.email_addresses');
                $results = $service->people_connections->listPeopleConnections('people/me',$optParams);
            
                $contacts = [];
                foreach($results->connections as $connection)
                {
                    $contact = [];
                    if(isset($connection->names))
                    {
                        $contact['name'] = $connection->names[0]->displayName;
                    }
                    
                    if(isset($connection->phoneNumbers))
                    {
                        $contact['phone'] = $connection->phoneNumbers[0]->canonicalForm;
                    }
                    
                    if(isset($connection->emailAddresses))
                    {
                        $contact['email'] = $connection->emailAddresses[0]->value;
                    }
                    
                    $contacts[] = $contact;
                }
                $providerUser->contacts = $contacts;
            }
            
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