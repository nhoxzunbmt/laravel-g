<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public $timestamps = false;
    protected $fillable = ['game_id', 'capt_id', 'slug', 'title', 'quantity', 'overlay', 'image'];
    
    /**
     * Пользователи, которые принадлежат данной команде.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'team_user');
    }
    
    /**
     * Fight which belongs to the team
     */
    public function fights()
    {
        return $this->belongsToMany('App\Models\Fight', 'fight_team');
    }
    
    /**
     * Fight which belongs to the team
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }
}
