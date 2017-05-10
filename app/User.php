<?php namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Cmgmyr\Messenger\Traits\Messagable;
use Hootlex\Friendships\Traits\Friendable;
  
class User extends Model implements AuthenticatableContract, CanResetPasswordContract 
{
	use Notifiable, Authenticatable, CanResetPassword, Messagable, Friendable;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'nickname'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) 
        {
            if(empty($user->nickname))
                $user->nickname = $user->email;
        });
    }
    
    /**
     * Set the password attribute.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
    
    /**
     * Команды, к которым принадлежит пользователь.
     */
    public function teams()
    {
        return $this->belongsToMany('App\Models\Team', 'team_user');
    }
    
    /**
     * Бои, к которым принадлежит пользователь.
     */
    public function fights()
    {
        return $this->belongsToMany('App\Models\Fight', 'fight_user', 'fight_id', 'user_id');
    }
    
    /**
     * Scope a query to only active scopes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('active', '=', 1);
    }
    
    /**
     * Scope a query to only player scopes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePlayer(Builder $query)
    {
        return $query->where('type', '=', 'player');
    }
    
    /**
     * Scope a query to only sponsor scopes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSponsor(Builder $query)
    {
        return $query->where('type', '=', 'sponsor');
    }
    
    /**
     * Scope a query to only commentator scopes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommentator(Builder $query)
    {
        return $query->where('type', '=', 'commentator');
    }    
    
    public static function createBySocialProvider($providerUser)
    {
        $email = $providerUser->getEmail();
        if(empty($email))
        {
            $email = self::generateEmail($providerUser);
        }
        
        return self::create([
            'email' => $email,
            'nickname' => $providerUser->getNickname(),
            'name' => $providerUser->getName(),
        ]);
    }
    
    public static function generateEmail($providerUser)
    {
        
    }
}
