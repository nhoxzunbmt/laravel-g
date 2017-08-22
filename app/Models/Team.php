<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public $timestamps = true;
    protected $fillable = ['game_id', 'capt_id', 'slug', 'title', 'quantity', 'overlay', 'image'];
    
    const PENDING = 0;
    const ACCEPTED = 1;
    
    /**
     * Пользователи, которые принадлежат данной команде.
     * @Relation
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'team_user')->active()->select('name', 'email', 'avatar', 'status', 'sender_id');;
    }
    
    /**
     * Пользователи, которые принадлежат данной команде.
     * @Relation
     */
    public function usersAccepted()
    {
        return $this->belongsToMany('App\User', 'team_user')->active()
            ->whereStatus(TeamUser::ACCEPTED)->select('name', 'email', 'avatar', 'status', 'sender_id');
    }
    
    /**
     * Fight which belongs to the team
     * @Relation
     */
    public function fights()
    {
        return $this->belongsToMany('App\Models\Fight');//, 'fight_team');
    }
    
    /**
     * Fight which belongs to the team
     * @Relation
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }
    
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
