<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    /**
     * votes table only has created_at (no updated_at).
     */
    public $timestamps = false;

    protected $fillable = [
        'player_id',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * A vote belongs to a player.
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
