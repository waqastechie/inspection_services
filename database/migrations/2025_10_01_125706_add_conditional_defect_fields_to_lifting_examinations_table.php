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
        Schema::table('lifting_examinations', function (Blueprint $table) {
            // Defect identification and classification
            $table->string('defect_location')->nullable();
            $table->enum('defect_type', ['structural', 'mechanical', 'electrical', 'hydraulic', 'wear', 'corrosion', 'fatigue', 'other'])->nullable();
            $table->enum('defect_severity', ['minor', 'moderate', 'major', 'critical'])->nullable();
            $table->integer('defect_extent')->nullable(); // percentage 0-100
            
            // Enhanced danger assessment
            $table->text('existing_danger_details')->nullable();
            $table->text('potential_danger_details')->nullable();
            $table->enum('defect_urgency', ['immediate', 'within_week', 'within_month', 'next_inspection', 'monitor'])->nullable();
            
            // Repair information
            $table->decimal('estimated_repair_cost', 10, 2)->nullable();
            $table->enum('estimated_repair_duration', ['hours', '1_day', '2_3_days', '1_week', '2_weeks', '1_month', 'longer'])->nullable();
            
            // Specialist requirements
            $table->enum('specialist_required', ['yes', 'no'])->nullable();
            $table->string('specialist_type')->nullable();
            $table->enum('out_of_service_required', ['yes', 'no'])->nullable();
            $table->text('out_of_service_reason')->nullable();
            
            // Follow-up actions
            $table->boolean('followup_retest')->default(false);
            $table->boolean('followup_reinspection')->default(false);
            $table->boolean('followup_monitoring')->default(false);
            $table->text('followup_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lifting_examinations', function (Blueprint $table) {
            $table->dropColumn([
                'defect_location',
                'defect_type',
                'defect_severity',
                'defect_extent',
                'existing_danger_details',
                'potential_danger_details',
                'defect_urgency',
                'estimated_repair_cost',
                'estimated_repair_duration',
                'specialist_required',
                'specialist_type',
                'out_of_service_required',
                'out_of_service_reason',
                'followup_retest',
                'followup_reinspection',
                'followup_monitoring',
                'followup_notes',
            ]);
        });
    }
};
