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
        return $this->belongsTo('App\Models\Game', 'game_id');
    }
       
    /**
     * Пользователь, создавший бой
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_id');
    }
    
    /**
     * Судья
     */
    public function judge()
    {
        return $this->belongsTo('App\User', 'judge_id');
    }
    
    /**
     * Комментатор
     */
    public function commentator()
    {
        return $this->belongsTo('App\User', 'commentator_id');
    }
    
    /**
     * Пользователь, отменивший бой
     */
    public function canceledBy()
    {
        return $this->belongsTo('App\User', 'cancel_user_id');
    }
}