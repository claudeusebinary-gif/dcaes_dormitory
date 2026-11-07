<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    /** Request types shown on the Approvals page. */
    public const TYPES = [
        'new_tenant' => 'Move-in',
        'transfer'   => 'Room transfer',
        'move_out'   => 'Move-out',
        'guest'      => 'Guest',
    ];

    protected $fillable = [
        'user_id', 'type', 'title', 'status',
        'room_number', 'bed_number', 'bed_deck', 'move_in_date', 'monthly_rent',
        'period', 'amount', 'fee', 'partial',
        'applicant_name', 'room_label', 'is_manual', 'checks',
        'occupation', 'civil_status', 'school', 'id_type', 'id_number', 'id_photo_path', 'length_of_stay', 'agreed',
    ];

    protected function casts(): array
    {
        return [
            'move_in_date' => 'date',
            'partial'      => 'boolean',
            'is_manual'    => 'boolean',
            'agreed'       => 'boolean',
            'checks'       => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Tenant's name, or the typed name for a manual record. */
    public function getNameAttribute(): string
    {
        return $this->user?->display_name ?? $this->applicant_name ?? 'Unknown';
    }

    /** "Room 103, Bed 2" for every kind of row (old rows read it from the title). */
    public function getRoomTextAttribute(): string
    {
        if ($this->room_number) {
            return 'Room ' . $this->room_number . ($this->bed_number ? ', Bed ' . $this->bed_number : '');
        }
        if ($this->room_label) {
            return $this->room_label;
        }

        return trim(str_replace('Applied for', '', (string) $this->title)) ?: '—';
    }
}