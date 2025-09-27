<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Gas Rack, Wire Rope, Bow Shackle, etc.
            $table->string('code')->unique(); // GAS_RACK, WIRE_ROPE, etc.
            $table->text('description')->nullable();
            $table->string('category')->default('general'); // lifting, testing, safety, etc.
            $table->json('default_services')->nullable(); // Default services for this type
            $table->json('required_fields')->nullable(); // Which fields are required for this type
            $table->json('specifications')->nullable(); // Type-specific specifications
            $table->boolean('requires_calibration')->default(true);
            $table->integer('calibration_frequency_months')->nullable(); // Default calibration frequency
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_types');
    }
};