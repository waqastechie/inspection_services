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
        Schema::create('other_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            
            // Drop Test fields
            $table->string('drop_test_load')->nullable();
            $table->string('drop_type')->nullable();
            $table->string('drop_distance')->nullable();
            $table->string('drop_suspended')->nullable();
            $table->string('drop_impact_speed')->nullable();
            $table->string('drop_result')->nullable();
            $table->text('drop_notes')->nullable();
            
            // Tilt Test fields
            $table->string('tilt_test_load')->nullable();
            $table->string('loaded_tilt')->nullable();
            $table->string('empty_tilt')->nullable();
            $table->string('tilt_results')->nullable();
            $table->string('tilt_stability')->nullable();
            $table->string('tilt_direction')->nullable();
            $table->string('tilt_duration')->nullable();
            $table->text('tilt_notes')->nullable();
            
            // Lowering Test fields
            $table->string('lowering_test_load')->nullable();
            $table->string('lowering_impact_speed')->nullable();
            $table->string('lowering_result')->nullable();
            $table->string('lowering_method')->nullable();
            $table->string('lowering_distance')->nullable();
            $table->string('lowering_duration')->nullable();
            $table->string('lowering_cycles')->nullable();
            $table->string('brake_efficiency')->nullable();
            $table->string('control_response')->nullable();
            $table->text('lowering_notes')->nullable();
            
            $table->timestamps();
            
            $table->index('inspection_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_tests');
    }
};
