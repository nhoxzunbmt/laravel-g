<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\LoginFormRequest;
use Carbon\Carbon;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Cache;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        User::create([
            'name' => $request->json('name'),
            'email' => $request->json('email'),
            'password' => $request->json('password'),
        ]);
    }
    
    public function signin(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');
        
        try {
            if(! $token = JWTAuth::attempt($credentials, [
                'exp' => Carbon::now()->addWeek()->timestamp,
            ])){
                return response()->json([
                    'error' => 'Invalid credentials'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token',
            ], 500);
        }
        
        //Add user online status
        $expiresAt = Carbon::now()->addMinutes(5);
        Cache::put('user-is-online-' . $request->user()->id, true, $expiresAt);

        $data = User::getApiUserData($request->user(), $token);
        return response()->json($data);
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        return response()->json(null, 204);
    }
}