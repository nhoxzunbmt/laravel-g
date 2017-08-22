<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FightTeam extends Model
{
    public $timestamps = false;
    protected $fillable = ['team_id', 'fight_id'];
    
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'fight_team';
}