<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceItem extends Model
{
  protected $fillable = ['seller_id', 'category', 'title', 'price', 'description', 'photos'];

protected function casts(): array
{
    return ['photos' => 'array'];
}

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
