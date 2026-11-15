<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectricityBill extends Model
{
    protected $fillable = ['room_number', 'period', 'amount', 'photo_path'];
}