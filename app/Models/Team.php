<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Acme\Helpers\ScheduleHelper;

class Team extends Model
{
    public $timestamps = true;
    protected $fillable = ['game_id', 'capt_id', 'slug', 'title', 'quantity', 'overlay', 'image', 'status', 'category_id', 'created_at', 'schedule'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'schedule' => 'array'
    ];
    
    const PENDING = 0;
    const ACCEPTED = 1;
    
    /**
     * Schedule mutators SET
     */
    public function setScheduleAttribute($array)
    {
        $dates = ScheduleHelper::convertObjectToArray($array);
        
        if(count($dates)>0)
        {
            $dates = json_encode($dates);
        }else{
            $dates = null;
        }
        
        $this->attributes['schedule'] = $dates;
    }
    
    /**
     * Schedule mutators GET
     */
    public function getScheduleAttribute($json)
    {
        $object = json_decode($json, true);
        return ScheduleHelper::convertArrayToObject($object, null);
    }
    
    /**
     * Пользователи, которые принадлежат данной команде.
     * @Relation
     */
    public function users()
    {
        return $this->hasMany('App\User')->active();
    }
    
    /**
     * Many to many (teams to users)
     * @Relation
     */
    public function invitations()
    {
        return $this->belongsToMany('App\User')->active();
    }
    
    /*public function users()
    {
        return $this->belongsToMany('App\User', 'team_user')->active()->select('name', 'email', 'avatar', 'status', 'sender_id');
    }
    public function usersAccepted()
    {
        return $this->belongsToMany('App\User', 'team_user')->active()
            ->whereStatus(TeamUser::ACCEPTED)->select('name', 'email', 'avatar', 'status', 'sender_id');
    }*/
    
    /**
     * Fight which belongs to the team
     * @Relation
     */
    public function fights()
    {
        return $this->belongsToMany('App\Models\Fight');
    }
    
    /**
     * Game which belongs to the team
     * @Relation
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }
    
    /**
     * Game which belongs to the team
     * @Relation
     */
    public function captain()
    {
        return $this->belongsTo('App\User', 'capt_id');
    }
    
    /**
     * @Relation
     */
    public function streams()
    {
        return $this->hasMany('App\Models\Stream', 'team_id');
    }
    
    /**
     * Search by params
     */
    public function scopeSearch($query, $request)
    {
        if(!empty($request['id']))
        {
            $query->where('id', (int)$request['id']);
        }
        if(!empty($request['status']))
        {
            $query->where('status', $request['status']);
        }
        if(!empty($request['game_id']))
        {
            $query->where('game_id', $request['game_id']);
        }
        return $query;
    }
}
