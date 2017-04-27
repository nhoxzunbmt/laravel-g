<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ganre extends Model
{
    public $timestamps = false;
    
    public function games()
    {
        return $this->hasMany('App\Game')
            ->active()
            ->orderBy('id', 'DESC');
    }
}