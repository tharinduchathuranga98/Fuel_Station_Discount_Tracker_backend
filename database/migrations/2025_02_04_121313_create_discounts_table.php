<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('number_plate'); // Connects to vehicle
            $table->decimal('total_liters', 8, 2); // Total fuel in the month
            $table->decimal('discount_amount', 8, 2); // Discount given
            $table->date('month'); // Discount for which month (YYYY-MM)
            $table->timestamps();
        });
    }

};
