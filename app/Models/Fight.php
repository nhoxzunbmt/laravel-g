<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fight extends Model
{
    /**
     * Пользователи, которые принадлежат данному бою.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'fight_user', 'user_id', 'fight_id');
    }
}
