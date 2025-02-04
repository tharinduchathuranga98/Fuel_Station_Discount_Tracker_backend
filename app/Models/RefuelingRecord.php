<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefuelingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_plate', // Vehicle number plate
        'liters', // Fuel quantity
        'refueled_at', // Date & time of refueling
    ];
}
