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
            // Additional load test fields that should be in load_tests table
            $additionalLoadTestFields = [
                'load_test_two_points_diagonal',
                'load_test_four_points', 
                'load_test_deflection',
                'load_test_deformation',
                'load_test_distance_from_ground'
            ];

            // Drop test fields that should be in other_tests table
            $dropTestFields = [
                'drop_test_load',
                'drop_type',
                'drop_distance',
                'drop_notes'
            ];

            // Tilt test fields that should be in other_tests table
            $tiltTestFields = [
                'tilt_test_load',
                'loaded_tilt',
                'tilt_notes'
            ];

            // Lowering test fields that should be in other_tests table
            $loweringTestFields = [
                'lowering_test_load',
                'lowering_impact_speed',
                'lowering_notes'
            ];

            // Combine all fields to drop
            $allFieldsToDrop = array_merge(
                $additionalLoadTestFields,
                $dropTestFields,
                $tiltTestFields,
                $loweringTestFields
            );

            // Drop columns if they exist
            foreach ($allFieldsToDrop as $field) {
                if (Schema::hasColumn('inspections', $field)) {
                    $table->dropColumn($field);
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
            // Additional load test fields
            $table->string('load_test_two_points_diagonal')->nullable();
            $table->string('load_test_four_points')->nullable();
            $table->string('load_test_deflection')->nullable();
            $table->string('load_test_deformation')->nullable();
            $table->string('load_test_distance_from_ground')->nullable();

            // Drop test fields
            $table->string('drop_test_load')->nullable();
            $table->string('drop_type')->nullable();
            $table->string('drop_distance')->nullable();
            $table->text('drop_notes')->nullable();

            // Tilt test fields
            $table->string('tilt_test_load')->nullable();
            $table->string('loaded_tilt')->nullable();
            $table->text('tilt_notes')->nullable();

            // Lowering test fields
            $table->string('lowering_test_load')->nullable();
            $table->string('lowering_impact_speed')->nullable();
            $table->text('lowering_notes')->nullable();
        });
    }
};
