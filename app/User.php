<?php namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Cmgmyr\Messenger\Traits\Messagable;
use Hootlex\Friendships\Traits\Friendable;
use \HighIdeas\UsersOnline\Traits\UsersOnlineTrait;
use Cache;
use Sofa\Eloquence\Eloquence;
  
class User extends Model implements AuthenticatableContract, CanResetPasswordContract 
{
	use Notifiable, Authenticatable, CanResetPassword, Messagable, Friendable, Eloquence, UsersOnlineTrait;

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
        'name', 'email', 'password', 'nickname', 'phone', 'last_name', 'second_name', 'avatar', 'min_sponsor_fee', 
        'overlay', 'description', 'type', 'country_id', 'confirmation_code', 'team_id', 'game_id', 'streams'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'confirmation_code'
    ];
    
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'streams' => 'array',
    ];
    
    protected $searchableColumns = [
        'nickname' => 20,
        'email' => 10,
        'name' => 10,
        'last_name' => 5
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
                
            if(empty($user->api_token))
                $user->api_token = str_random(100);
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @Relation
     */
    public function country()
    {
        return $this->belongsTo('\Webpatser\Countries\Countries');
    }
    
    /**
     * User's team.
     * @Relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }
    
    /**
     * User's team history.
     * @Relation
     */
    public function teams()
    {
        return $this->belongsToMany('App\Models\Team');
    }
    
    /**
     * User's game
     * @Relation
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }
    
    /**
     * Бои, к которым принадлежит пользователь.
     * @Relation
     */
    /*public function fights()
    {
        return $this->belongsToMany('App\Models\Fight');
    }*/
    
    /**
     * Fights which are created by user
     * @Relation
     */
    public function createdFights()
    {
        return $this->hasMany('App\Models\Fight', 'created_id');
    }
    
    /**
     * User is judge of fights
     * @Relation
     */
    public function judgeOfFights()
    {
        return $this->hasMany('App\Models\Fight', 'judge_id');
    }
    
    /**
     * User is commentator of fights
     * @Relation
     */
    public function commentatorOfFights()
    {
        return $this->belongsTo('App\Models\Fight', 'commentator_id');
    }
    
    /**
     * Figths canceled by user
     * @Relation
     */
    public function canceledFights()
    {
        return $this->belongsTo('App\Models\Fight', 'cancel_user_id');
    }
    
    /**
     * Scope a query to only active scopes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
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
    public function scopePlayer($query)
    {
        return $query->where('type', '=', 'player');
    }
    
    /**
     * Scope a query to only investor scopes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInvestor($query)
    {
        return $query->where('type', '=', 'investor');
    }   
    
    
    /**
     * Create user from social account
     */
    public static function createBySocialProvider($providerUser)
    {
        $email = $providerUser->getEmail();
        if(empty($email))
        {
            $email = self::generateEmail($providerUser);
        }
                              
        $data = [
            'email' => $email,
            'nickname' => $providerUser->getNickname(),
            'name' => $providerUser->getName() ? $providerUser->getName() : $providerUser->getNickname(),
            'password' => str_random(10),
            'avatar' => $providerUser->getAvatar(),
            'confirmed' => 1           
        ];                               
        
        return self::create($data);
    }
    
    public static function generateEmail($providerUser)
    {
        $site = env('APP_URL', "games.dev");
        $site = str_replace(["http://", "https://"], "", $site);
        $email = $providerUser->getNickname()."@".$site;
        return $email;
    }
    
    /*public static function getApiUserData($user, $token = false)
    {
        $data = [];
        $meta = [];

        $data['name'] = $user->name;
        $data['avatar'] = $user->avatar;
        $data['type'] = $user->type;
        $data['online'] = $user->isOnline();
        
        $data = $user;
        if($data->avatar)
            $data->avatar = asset('storage/'.$data->avatar);
        if($data->overlay)
            $data->overlay = asset('storage/'.$data->overlay);
        
        if($token)
            $meta['token'] = $token;
        
        return [
            'data' => $data,
            'meta' => $meta
        ];
    }*/
    
    /**
     * @Relation
     */
    public function fightStreams()
    {
        return $this->hasMany('App\Models\Stream', 'user_id');
    }
    
    /**
     * Search by params
     */
    public function scopeFilter($query, $request)
    {
        if(!empty($request['type']))
        {
            $query->whereType($request['type']);
        }
        return $query;
    }
}
