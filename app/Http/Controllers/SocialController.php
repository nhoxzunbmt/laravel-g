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

class SocialController extends Controller
{

    public function login($provider)
    {
        return Socialite::with($provider)->redirect();
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
            return response()->json([
                'error' => 'Could not create token',
            ], 500);
        }
   
        $data = User::getApiUserData($user, $token);
        return response()->json($data);
        //\Auth::login($user, true);
        //return redirect()->intended('/');
    }

}