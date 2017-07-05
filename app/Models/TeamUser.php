<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamUser extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id', 'team_id', 'status'];
    
    const PENDING = 0;
    const ACCEPTED = 1;
    const DENIED = 2;
    
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'team_user';
}