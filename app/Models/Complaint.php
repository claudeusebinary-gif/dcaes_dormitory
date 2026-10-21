<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    public const TYPES = [
        'plumbing'           => 'Plumbing',
        'electrical'         => 'Electrical',
        'room_furniture'     => 'Room and furniture',
        'cleanliness'        => 'Cleanliness',
        'pests'              => 'Pests',
        'noise'              => 'Noise and disturbance',
        'roommate'           => 'Roommate concern',
        'security'           => 'Security and safety',
        'internet_utilities' => 'Internet and utilities',
        'billing'            => 'Billing and payments',
        'staff'              => 'Staff or management',
        'other'              => 'Others',
    ];

    protected $fillable = ['user_id', 'room_number', 'type', 'message', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }
}