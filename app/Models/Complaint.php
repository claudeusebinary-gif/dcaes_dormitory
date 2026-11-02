<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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

   protected $fillable = ['user_id', 'room_number', 'type', 'message', 'status', 'in_progress_at', 'resolved_at'];

protected $casts = ['in_progress_at' => 'datetime', 'resolved_at' => 'datetime'];

public const HIGH_TYPES = ['plumbing', 'electrical', 'security', 'pests'];

public function getIsHighAttribute(): bool
{
    return in_array($this->type, self::HIGH_TYPES);
}

public function getTitleAttribute(): string
{
    $first = preg_split('/[.!?\n]/', trim($this->message))[0] ?? '';
    return Str::limit(trim($first), 60);
}

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
public function messages(): HasMany
{
    return $this->hasMany(ComplaintMessage::class);
}
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }
}