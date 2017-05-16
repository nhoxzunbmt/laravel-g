<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    public $timestamps = false;
    
    public function games()
    {
        return $this->hasMany(Game::class)
            ->active()
            ->orderBy('id', 'DESC');
    }
}