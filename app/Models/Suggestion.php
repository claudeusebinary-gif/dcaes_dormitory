<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suggestion extends Model
{
    protected $fillable = ['user_id', 'room_number', 'message', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}