<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    protected $fillable = ['tenant_id', 'title', 'message', 'is_read'];

    protected function casts(): array
    {
        return ['is_read' => 'boolean'];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    /** One-line helper: AdminNotification::fromTenant($user, 'Title', 'did something.') */
    public static function fromTenant(User $tenant, string $title, string $what): self
    {
        $room = $tenant->room_number ? ' (Room ' . $tenant->room_number . ')' : '';

        return static::create([
            'tenant_id' => $tenant->id,
            'title'     => $title,
            'message'   => $tenant->display_name . $room . ' ' . $what,
        ]);
    }
}