<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $fillable = [
        'name',
        'position',
        'photo_url',
        'vote_count',
    ];

    protected $casts = [
        'vote_count' => 'integer',
    ];

    /**
     * A player has many votes.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
