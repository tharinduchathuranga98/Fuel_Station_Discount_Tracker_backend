<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FuelType extends Model
{
    protected $fillable = ['name', 'price', 'code'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($fuelType) {
            // Get the last fuel type
            $lastFuelType = self::latest()->first();
            $nextNumber = $lastFuelType ? ((int)substr($lastFuelType->code, 5)) + 1 : 1;

            // Generate code in format: FUEL-001, FUEL-002, etc.
            $fuelType->code = 'FUEL-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }
}
