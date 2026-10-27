<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomRequest extends Model
{
    protected $fillable = [
        'user_id', 'type', 'room_number', 'category', 'urgency',
        'target_room', 'target_bed', 'target_deck', 'reason', 'details', 'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}