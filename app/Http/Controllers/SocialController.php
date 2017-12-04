<?php

namespace App\Http\Controllers;

//use App\Http\Requests;
use App\Services\SocialAccountService;
use Socialite;
use Carbon\Carbon;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Google_Client;
use Google_Service_People;

class SocialController extends Controller
{
    public function login($provider)
    {
        if($provider=="google")
        {
            return Socialite::with($provider)
                ->scopes(['openid', 'profile', 'email', Google_Service_People::CONTACTS_READONLY])
                ->redirect();
            
        }else{
            return Socialite::with($provider)->redirect();
        }
    }

    public function callback(SocialAccountService $service, $provider)
    {        
        $driver = Socialite::driver($provider);
        
        if($provider=="google")
        {
            $user = $driver->user();

            // Установим токен в  Google API PHP Client
            $google_client_token = [
                'access_token' => $user->token,
                'refresh_token' => $user->refreshToken,
                'expires_in' => $user->expiresIn
            ];
        
            $client = new Google_Client();
            $client->setApplicationName(env("GOOGLE_APP_NAME"));
            $client->setDeveloperKey(env('GOOGLE_SERVER_KEY'));
            $client->setAccessToken(json_encode($google_client_token));
            // Запросим контакты пользователя
            $service = new Google_Service_People($client);
        
            $optParams = array('requestMask.includeField' => 'person.phone_numbers,person.names,person.email_addresses');
            $results = $service->people_connections->listPeopleConnections('people/me',$optParams);
        
            dd($results);
        }
        
        $user = $service->createOrGetUser($driver, $provider);
        
        if(!$user)
        {
            return response()->json([
                'error' => 'Could not create user',
            ], 500);
        }
        
        if(!$token = JWTAuth::fromUser($user))
        {
            $token = str_random(256);
            JWTAuth::setToken($token);
            /*return response()->json([
                'error' => 'Could not create token',
            ], 500);*/
        }
   
        $payload = JWTAuth::getPayload($token);
        $expiration = $payload['exp'];
        
        /*return response()->json([
            "token_type" => "Bearer",
            'token' => $token,
            'expires_in' => $expiration
        ], 200);*/
        
        return view('social_callback', [
            "token_type" => "Bearer",
            'token' => $token,
            'expires_in' => $expiration
        ]);
    }

}