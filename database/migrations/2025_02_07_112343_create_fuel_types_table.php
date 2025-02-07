<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fuel_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Auto-generated code like FUEL-001
            $table->string('name')->unique(); // Fuel type name (e.g., Petrol, Diesel)
            $table->decimal('price', 10, 2); // Fuel price (e.g., 350.50)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_types');
    }
};
