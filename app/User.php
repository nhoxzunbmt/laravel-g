<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    public static function createBySocialProvider($providerUser)
    {
        $email = $providerUser->getEmail();
        if(empty($email))
        {
            $email = self::generateEmail($providerUser);
        }
        
        return self::create([
            'email' => $providerUser->getEmail(),
            'username' => $providerUser->getNickname(),
            'name' => $providerUser->getName(),
        ]);
    }
    
    public static function generateEmail($providerUser)
    {
        //env('APP_URL')
    }
}
