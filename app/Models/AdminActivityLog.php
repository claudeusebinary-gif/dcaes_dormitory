<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    protected $fillable = ['admin_id', 'admin_name', 'section', 'action', 'changes', 'ip'];

    protected function casts(): array
    {
        return ['changes' => 'array'];
    }
}