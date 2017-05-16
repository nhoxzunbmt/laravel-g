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
    public function scopeActive($query)
    {
        return $query->where('active', '=', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function genre()
    {
        return $this->hasOne('App\Models\Genre', 'id', 'genre_id');
    }
    
    /**
     * Get the fights that owns the game.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fight()
    {
        return $this->belongsTo('App\Models\Fight');
    }
}