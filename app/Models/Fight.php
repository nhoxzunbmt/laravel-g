<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Fight extends Model
{
    protected $dates = ['start_at'];
    protected $fillable = ['start_at', 'title', 'game_id', 'created_id', 'count_team_users', 'count_parts', 'active'];
    
    /**
     * Команды, которые принадлежат данному бою.
     * @Relation
     */
    public function teams()
    {
        return $this->belongsToMany('App\Models\Team');
    }
    
    /**
     * Игра, в которую будут играть в бою.
     * @Relation
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'game_id');
    }
       
    /**
     * Пользователь, создавший бой
     * @Relation
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_id');
    }
    
    /**
     * Судья
     * @Relation
     */
    public function judge()
    {
        return $this->belongsTo('App\User', 'judge_id');
    }
    
    /**
     * Комментатор
     * @Relation
     */
    public function commentator()
    {
        return $this->belongsTo('App\User', 'commentator_id');
    }
    
    /**
     * Пользователь, отменивший бой
     * @Relation
     */
    public function canceledBy()
    {
        return $this->belongsTo('App\User', 'cancel_user_id');
    }
    
    /**
     * @Relation
     */
    public function streams()
    {
        return $this->hasMany('App\Models\Stream', 'fight_id');
    }
    
    public function scopePublished($query)
    {
        $query->where('start_at', '>=', Carbon::now())->where('active', true)->where('canceled', false);
    }
    
    public function setStartAtAttribute($date)
    {
        $this->attributes['start_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $date);
    }
}