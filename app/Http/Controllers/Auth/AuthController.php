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
use App\Mail\EmailVerify;
use Mail;

class AuthController extends Controller
{
    public function verify($confirmation_code)
    {
        if( ! $confirmation_code)
        {
            return response()->json([
                'error' => 'No confirmation code',
            ], 500);
        }

        $user = User::where('confirmation_code', $confirmation_code)->first();

        if ( ! $user)
        {
            return response()->json([
                'error' => 'Wrong confirmation code'.$confirmation_code,
            ], 500);
        }

        $user->confirmed = 1;
        
        if(!empty($user->type))
        {
            $user->active = 1;
        }
        
        $user->confirmation_code = null;
        $user->save();
        
        return response()->json(['status' => 'Your account is now verified!'], 200);
    }
    
    
    public function register(RegisterFormRequest $request)
    {
        $confirmation_code = str_random(100);
        
        User::create([
            'name' => $request->json('name'),
            'email' => $request->json('email'),
            'password' => $request->json('password'),
            'confirmation_code' => $confirmation_code
        ]);
        
        $content = [
    		'url' => url(config('app.url')."/auth/verify/".$confirmation_code),
            'title' => 'Verify your email address',
    		'button' => 'Click Here'
  		];

    	Mail::to($request->json('email'))->send(new EmailVerify($content));
        
        /*Mail::send('email.verify', ['confirmation_code' => $confirmation_code], function($message) use ($request) {
            $message->to($request->json('email'), $request->json('name'))
                ->subject('Verify your email address');
        });*/
    }
    
    public function signin(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');
        
        try {
            if(! $token = JWTAuth::attempt($credentials, [
                'exp' => Carbon::now()->addWeek()->timestamp,
            ])){
                return response()->json([
                    'error' => 'Invalid credentials.'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token',
            ], 500);
        }
        
        $user = $request->user();
        
        if(!$user->confirmed)
        {
            $confirmation_code = str_random(100);
            $user->confirmation_code = $confirmation_code;
            $user->update();
            
            $content = [
        		'url' => url(config('app.url')."/auth/verify/".$confirmation_code),
                'title' => 'Verify your email address',
        		'button' => 'Click Here'
      		];
    
        	Mail::to($user->email)->send(new EmailVerify($content));
            
                      
            /*Mail::send('email.verify', ['confirmation_code' => $confirmation_code], function($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('Verify your email address');
            });*/
            
            return response()->json([
                'error' => 'You didn\'t confirm email. Check your email box, you should recieve email with confirmation link.',
                'user' => $request->user()
            ], 401);
        }

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
        $request->user()->pullCache();  //clear UserOnline cache
        return response()->json(null, 204);
    }
}