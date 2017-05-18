<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
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
}
