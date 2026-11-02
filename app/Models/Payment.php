<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
       protected $fillable = ['user_id', 'period', 'amount', 'fee', 'method', 'reference', 'paid_at', 'partial'];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime', 'partial' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}