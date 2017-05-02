<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = false;
    
    /**
     * Scope a query to only active scopes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('active', '=', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ganre()
    {
        return $this->hasOne(Ganre::class, 'id', 'ganre_id');
    }
}
