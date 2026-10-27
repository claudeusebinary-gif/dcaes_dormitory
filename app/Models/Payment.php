<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id', 'period', 'amount', 'method', 'reference', 'paid_at'];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime'];
    }
}