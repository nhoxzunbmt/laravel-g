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
    
    /**
     * Команды, которые принадлежат данному бою.
     */
    public function teams()
    {
        return $this->belongsToMany('App\Models\Team', 'fight_team', 'team_id', 'fight_id');
    }
    
    /**
     * Игра, в которую будут играть в бою.
     */
    public function game()
    {
        return $this->hasOne('App\Models\Game', 'id', 'game_id');
    }
       
    /**
     * Пользователь, создавший бой
     */
    public function createdBy()
    {
        return $this->hasOne('App\User', 'id', 'created_id');
    }
    
    /**
     * Судья
     */
    public function judge()
    {
        return $this->hasOne('App\User', 'id', 'judge_id');
    }
    
    /**
     * Комментатор
     */
    public function commentator()
    {
        return $this->hasOne('App\User', 'id', 'commentator_id');
    }
    
    /**
     * Пользователь, отменивший бой
     */
    public function canceledBy()
    {
        return $this->hasOne('App\User', 'id', 'cancel_user_id');
    }
}