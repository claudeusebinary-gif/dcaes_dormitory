<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_number', 'sharing_type', 'bed_type',
        'total_beds', 'price_per_bed', 'photo',
        'under_maintenance', 'photos', 'amenities',
    ];

    protected function casts(): array
    {
          return [
       'price_per_bed'     => 'decimal:2',
       'under_maintenance' => 'boolean',
       'photos'            => 'array',
        'amenities'         => 'array',
   ];
    }
public function getPhotoPathsAttribute(): array
{
    $list = $this->photos ?: ($this->photo ? [$this->photo] : []);
    return array_values($list);
}
    public function getPriceLabelAttribute(): string
    {
        return '₱' . number_format((float) $this->price_per_bed) . ' / mo';
    }
}