<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->string('number_plate')->unique();  // Vehicle number plate
        $table->string('owner_name');  // Owner's name
        $table->string('owner_phone');  // Owner's phone number
        $table->longText('qr_code')->unique();  // QR code for the vehicle
        // $table->longText('qr_code')->change();
        $table->timestamps();
    });
}

};
