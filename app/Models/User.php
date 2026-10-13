<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

  protected $fillable = [
    'full_name', 'email', 'password', 'role',
    'room_number', 'bed_number', 'bed_deck', 'phone_number',
    'move_in_date', 'monthly_rent', 'payment_status',
    'sex', 'birthday', 'address',
];

public function emergencyContact(): HasOne
{
    return $this->hasOne(EmergencyContact::class);
}

public function getBirthdayLabelAttribute(): string
{
    return $this->birthday
        ? Carbon::parse($this->birthday)->format('F j, Y')
        : 'Not set';
}

public function getAgeAttribute(): ?int
{
    return $this->birthday ? Carbon::parse($this->birthday)->age : null;
}
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
{
    return [
        'password' => 'hashed',
        'move_in_date' => 'date',
        'monthly_rent' => 'decimal:2',
        'birthday' => 'date',
    ];
}
public function getMoveInDateLabelAttribute(): string
{
    return $this->move_in_date
        ? Carbon::parse($this->move_in_date)->format('F j, Y')
        : 'Not set';
}

public function getMonthlyRentLabelAttribute(): string
{
    return $this->monthly_rent !== null
        ? '₱' . number_format((float) $this->monthly_rent)
        : 'Not set';
}
public function roommates(): HasMany
{
    return $this->hasMany(User::class, 'room_number', 'room_number')
        ->where('role', 'tenant')
        ->where('id', '!=', $this->id);
}
/** e.g. "2 (Upper Deck)", or "Not assigned" */
public function getBedLabelAttribute(): string
{
    if (! $this->bed_number) {
        return 'Not assigned';
    }

    return $this->bed_deck
        ? $this->bed_number . ' (' . ucfirst($this->bed_deck) . ' Deck)'
        : $this->bed_number;
}
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function tenantNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'tenant_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?: ucfirst(strstr($this->email, '@', true) ?: $this->email);
    }
public function setBedDeckAttribute($value): void
{
    $this->attributes['bed_deck'] = $value ? ucfirst(strtolower($value)) : null;
}
    /** First letter of the display name, used for the sidebar avatar. */
    public function getInitialAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->display_name, 0, 1));
    }
    public function getFirstNameAttribute(): string
{
    return explode(' ', trim($this->display_name))[0];
}
public function getPaymentStatusLabelAttribute(): string
{
    return match ($this->payment_status) {
        'up_to_date' => 'Up to Date',
        'overdue'    => 'Overdue',
        default      => 'Payment Due',
    };
}

/** CSS class used to color the status text. */
public function getPaymentStatusClassAttribute(): string
{
    return match ($this->payment_status) {
        'up_to_date' => 'status-ok',
        'overdue'    => 'status-bad',
        default      => 'status-warn',
    };
}

}
