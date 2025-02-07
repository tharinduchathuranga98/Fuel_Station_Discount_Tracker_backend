<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('refueling_records', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2); // Store total price with 2 decimal places
        });
    }
};
