<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void
    {
        Schema::create('refueling_records', function (Blueprint $table) {
            $table->id();
            $table->string('number_plate');  // Connects to vehicle number plate
            $table->integer('liters');  // Fuel quantity
            $table->timestamp('refueled_at')->useCurrent();  // Date & time of refueling
            $table->timestamps();
        });
    }

};
