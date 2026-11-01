<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
       protected $fillable = [
        'user_id', 'type', 'title', 'status',
        'room_number', 'bed_number', 'bed_deck', 'move_in_date', 'monthly_rent',
    ];

    protected function casts(): array
    {
        return ['move_in_date' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}