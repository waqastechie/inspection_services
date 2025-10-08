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
        Schema::table('inspections', function (Blueprint $table) {
            // Drop test related columns (should be in other_tests table)
            $dropTestColumns = [
                'drop_suspended',
                'drop_impact_speed', 
                'drop_result'
            ];

            // Tilt test related columns (should be in other_tests table)
            $tiltTestColumns = [
                'empty_tilt',
                'tilt_results',
                'tilt_stability', 
                'tilt_direction',
                'tilt_duration'
            ];

            // Lowering test related columns (should be in other_tests table)
            $loweringTestColumns = [
                'lowering_result',
                'lowering_method',
                'lowering_distance',
                'lowering_duration',
                'lowering_cycles'
            ];

            // Thorough examination columns (should be in lifting_examinations table)
            $thoroughExaminationColumns = [
                'thorough_method',
                'thorough_equipment',
                'thorough_conditions',
                'thorough_results'
            ];

            // Visual examination columns (should be in mpi_inspections table)
            $visualExaminationColumns = [
                'visual_method',
                'visual_lighting',
                'visual_equipment', 
                'visual_conditions',
                'visual_results'
            ];

            // Lifting examination specific columns (should be in lifting_examinations table)
            $liftingExaminationColumns = [
                'first_examination',
                'equipment_defect',
                'defect_description'
            ];

            // Combine all service-specific columns to drop
            $allServiceColumns = array_merge(
                $dropTestColumns,
                $tiltTestColumns,
                $loweringTestColumns,
                $thoroughExaminationColumns,
                $visualExaminationColumns,
                $liftingExaminationColumns
            );

            // Drop columns if they exist
            foreach ($allServiceColumns as $column) {
                if (Schema::hasColumn('inspections', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            // Drop test columns
            $table->string('drop_suspended')->nullable();
            $table->string('drop_impact_speed')->nullable();
            $table->string('drop_result')->nullable();

            // Tilt test columns
            $table->string('empty_tilt')->nullable();
            $table->string('tilt_results')->nullable();
            $table->string('tilt_stability')->nullable();
            $table->string('tilt_direction')->nullable();
            $table->integer('tilt_duration')->nullable();

            // Lowering test columns
            $table->string('lowering_result')->nullable();
            $table->string('lowering_method')->nullable();
            $table->decimal('lowering_distance', 8, 2)->nullable();
            $table->integer('lowering_duration')->nullable();
            $table->integer('lowering_cycles')->nullable();

            // Thorough examination columns
            $table->string('thorough_method')->nullable();
            $table->string('thorough_equipment')->nullable();
            $table->string('thorough_conditions')->nullable();
            $table->string('thorough_results')->nullable();

            // Visual examination columns
            $table->string('visual_method')->nullable();
            $table->string('visual_lighting')->nullable();
            $table->string('visual_equipment')->nullable();
            $table->string('visual_conditions')->nullable();
            $table->string('visual_results')->nullable();

            // Lifting examination columns
            $table->enum('first_examination', ['yes', 'no'])->nullable();
            $table->enum('equipment_defect', ['yes', 'no'])->nullable();
            $table->text('defect_description')->nullable();
        });
    }
};
