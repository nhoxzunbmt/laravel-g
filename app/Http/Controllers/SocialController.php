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
        }
   
        $payload = JWTAuth::getPayload($token);
        $expiration = $payload['exp'];
        
        return view('social_callback', [
            "token_type" => "Bearer",
            'token' => $token,
            'expires_in' => $expiration
        ]);
    }

}