<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['number_plate', 'owner_name', 'owner_phone', 'qr_code', 'fuel_type', 'category'];
}
