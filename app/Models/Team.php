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
}
