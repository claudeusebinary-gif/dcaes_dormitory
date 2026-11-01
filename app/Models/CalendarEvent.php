<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CalendarEvent extends Model
{
    protected $fillable = ['user_id', 'title', 'type', 'event_date', 'event_time', 'location', 'description'];

    protected function casts(): array
    {
        return ['event_date' => 'date'];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'calendar_event_user', 'calendar_event_id', 'user_id')->withTimestamps();
    }
}