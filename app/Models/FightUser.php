<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FightUser extends Model
{
    public $timestamps = false;
    
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'fight_user';
}