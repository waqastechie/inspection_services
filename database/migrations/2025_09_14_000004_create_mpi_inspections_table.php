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
        Schema::create('mpi_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            // Remove inspection_service_id since we don't need the intermediate table
            
            // Inspector assignment (matches HTML form field: mpi_service_inspector)
            $table->foreignId('inspector_id')->nullable()->constrained('personnels')->onDelete('set null');
            
            // Form fields from mpi-service.blade.php
            $table->string('contrast_paint_method')->nullable(); // spray, brush, roller, dip
            $table->string('ink_powder_1_method')->nullable(); // wet_continuous, wet_residual, dry_continuous, dry_residual
            $table->string('magnetic_particle_concentration')->nullable();
            $table->string('current_flow')->nullable(); // longitudinal, circular, multidirectional
            $table->string('ink_powder_1_carrier')->nullable(); // water, oil, kerosene, conditioner
            $table->string('contact_spacing')->nullable();
            $table->string('magnetic_flow')->nullable(); // ac, dc, pulsed_dc, three_phase
            $table->string('ink_powder_2_method')->nullable(); // wet_continuous, wet_residual, dry_continuous, dry_residual
            $table->string('field_application_time')->nullable();
            $table->string('ink_powder_2_carrier')->nullable(); // water, oil, kerosene, conditioner
            $table->string('black_light_intensity_begin')->nullable();
            $table->string('black_light_intensity_end')->nullable();
            $table->string('test_temperature')->nullable(); // ambient, elevated, controlled
            $table->string('pull_test')->nullable(); // performed, not_required, failed
            $table->string('post_test_cleaning')->nullable(); // water_rinse, solvent_clean, mechanical, not_required
            $table->string('initial_demagnetisation')->nullable(); // performed, not_required, partial
            $table->string('final_demagnetisation')->nullable(); // performed, not_required, partial, failed
            $table->text('mpi_results')->nullable();
            
            $table->timestamps();
            
            $table->index('inspection_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpi_inspections');
    }
};
