<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamUser extends Model
{
    public $timestamps = false;
    
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'team_user';
}