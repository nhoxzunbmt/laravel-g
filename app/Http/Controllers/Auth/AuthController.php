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
    protected $username = 'nickname';
    
    /**
     * Verify email by confirmation_code from message
     * 
     * @param string $confirmation_code
     */
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
        
        return response()->json([
            'message' => 'Your account is now verified!'
        ], 200);
    }
    
    
    /**
     * Register user by params
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterFormRequest $request)
    {
        $confirmation_code = str_random(100);
        
        User::create([
            'name' => $request->json('nickname'),
            'nickname' => $request->json('nickname'),
            'email' => $request->json('email'),
            'password' => $request->json('password'),
            'confirmation_code' => $confirmation_code,
            'type' => $request->json('type')
        ]);
        
        $content = [
    		'url' => url(config('app.url')."/email/verify/".$confirmation_code),
            'title' => 'Verify your email address',
    		'button' => 'Click Here'
  		];

    	Mail::to($request->json('email'))->send(new EmailVerify($content));
        
        return response()->json([
            'message' => 'We sent you an activation code. Check your email.'
        ], 200);
    }
    
    /**
     * Login user by params
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginFormRequest $request)
    {
        //$credentials = $request->only('email', 'password');
        $credentials = $request->only('nickname', 'password');
        
        $expiration = Carbon::now()->addWeek()->timestamp;
        try {
            if(! $token = JWTAuth::attempt($credentials, [
                'exp' => $expiration,
            ])){
                
                if(filter_var($request->get('nickname'), FILTER_VALIDATE_EMAIL))
                {
                    $q = User::where("email", $request->get('nickname'));
                    if($q->count()>0)
                    {
                        return response()->json([
                            'error' => 'Choose your nickname from the list:',
                            'data' => $q->select(['nickname'])->get()
                        ], 401);
                    }
                }
                
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
            
            if(!empty($user->email))
            {
                $content = [
            		'url' => url(config('app.url')."/email/verify/".$confirmation_code),
                    'title' => 'Verify your email address',
            		'button' => 'Click Here'
          		];
            }
    
        	Mail::to($user->email)->send(new EmailVerify($content));
            
            /*Mail::send('email.verify', ['confirmation_code' => $confirmation_code], function($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('Verify your email address');
            });*/
            
            return response()->json([
                'error' => 'You didn\'t confirm email. Check your email box, you should recieve email with confirmation link.',
                //'user' => $request->user()
            ], 401);
        }
        
        //$payload = JWTAuth::parseToken()->getPayload();
        //$payload = JWTAuth::decode($token);
        $payload = JWTAuth::getPayload($token);
        $expiration = $payload['exp'];
        
        return response()->json([
            "token_type" => "Bearer",
            'token' => $token,
            'expires_in' => $expiration
        ], 200);
        //$data = User::getApiUserData($request->user(), $token);
        //return response()->json($data);
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        //$request->user()->pullCache();  //clear UserOnline cache
        $token = JWTAuth::getToken();
        JWTAuth::setToken($token)->invalidate();
        return response()->json([
             "message" => "Token revoked successfully."
        ], 202);
    }
    
    
    /**
     * Get token with refreshing
     */
    public function token()
    {
        $token = JWTAuth::getToken();

        if (! $token) {
            throw new BadRequestHttpException('Token not provided');
        }

        try {
            $token = JWTAuth::refresh($token);
        } catch (TokenInvalidException $e) {
            throw new AccessDeniedHttpException('The token is invalid');
        }
        
        $payload = JWTAuth::parseToken()->getPayload();
        $expiration = $payload->getExp();
        return response()->json([
            "token_type" => "Bearer",
            'token' => $token,
            'expires_in' => $expiration
        ], 200);
    }
}