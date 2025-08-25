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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // e.g., 'crane', 'hoist', 'measuring_device'
            $table->string('brand_model')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('calibration_date')->nullable();
            $table->date('calibration_due')->nullable();
            $table->string('calibration_certificate')->nullable();
            $table->enum('condition', ['excellent', 'good', 'fair', 'needs_maintenance', 'out_of_service'])->default('good');
            $table->decimal('usage_hours', 10, 2)->nullable();
            $table->text('maintenance_notes')->nullable();
            $table->text('specifications')->nullable(); // JSON field for technical specs
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('type');
            $table->index('condition');
            $table->index('is_active');
            $table->index('calibration_due');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
