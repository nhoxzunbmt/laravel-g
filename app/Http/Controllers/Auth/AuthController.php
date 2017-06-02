<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use Carbon\Carbon;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

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
    
    public function signin(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        
        try {
            $token = JWTAuth::attempt($credentials, [
                'exp' => Carbon::now()->addWeek()->timestamp,
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not authenticate',
            ], 500);
        }

        if (!$token) {
            return response()->json([
                'error' => 'Could not authenticate',
            ], 401);
        } else {
            $data = [];
            $meta = [];

            $data['name'] = $request->user()->name;
            $data['avatar'] = $request->user()->avatar;
            $meta['token'] = $token;

            return response()->json([
                'data' => $data,
                'meta' => $meta
            ]);
        }
    }
}