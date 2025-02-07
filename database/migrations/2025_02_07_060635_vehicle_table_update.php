<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('fuel_type'); // Petrol, Diesel, etc.
            $table->string('category'); // Car, Truck, Motorcycle, etc.
        });
    }
};
