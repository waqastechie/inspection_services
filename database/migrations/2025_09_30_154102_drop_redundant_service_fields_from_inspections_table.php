<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('inspections', function (Blueprint $table) {
            // First, drop foreign key constraints that might exist
            $foreignKeyFields = [
                'load_test_inspector',
                'mpi_service_inspector', 
                'visual_inspector',
                'lifting_examination_inspector',
                'thorough_examination_inspector'
            ];

            foreach ($foreignKeyFields as $field) {
                if (Schema::hasColumn('inspections', $field)) {
                    try {
                        // Try different possible foreign key naming conventions
                        $possibleNames = [
                            'inspections_' . $field . '_foreign',
                            'inspections_' . $field . '_index',
                            $field . '_foreign'
                        ];
                        
                        foreach ($possibleNames as $fkName) {
                            try {
                                $table->dropForeign($fkName);
                                break; // If successful, break out of inner loop
                            } catch (\Exception $e) {
                                // Try next name
                                continue;
                            }
                        }
                    } catch (\Exception $e) {
                        // Foreign key doesn't exist or has different name, continue
                    }
                }
            }
        });

        // Second migration step - drop the columns
        Schema::table('inspections', function (Blueprint $table) {
            // Fields moved to load_tests table
            $loadTestFields = [
                'load_test_inspector',
                'load_test_duration',
                'load_test_notes',
                'load_test_result',
                'load_test_comments'
            ];

            // Fields moved to mpi_inspections table
            $mpiFields = [
                'mpi_service_inspector',
                'visual_inspector',
                'visual_comments',
                'visual_method',
                'visual_lighting',
                'visual_equipment',
                'visual_conditions',
                'visual_results'
            ];

            // Fields moved to lifting_examinations table
            $liftingFields = [
                'lifting_examination_inspector',
                'thorough_examination_inspector',
                'thorough_examination_comments',
                'thorough_method',
                'thorough_equipment',
                'thorough_conditions',
                'thorough_results'
            ];

            // Drop columns if they exist
            $allFields = array_merge($loadTestFields, $mpiFields, $liftingFields);
            
            foreach ($allFields as $field) {
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
            // Re-add Load Test fields
            $table->unsignedBigInteger('load_test_inspector')->nullable();
            $table->string('load_test_duration')->nullable();
            $table->string('load_test_two_points_diagonal')->nullable();
            $table->string('load_test_four_points')->nullable();
            $table->string('load_test_deflection')->nullable();
            $table->string('load_test_deformation')->nullable();
            $table->string('load_test_distance_from_ground')->nullable();
            $table->string('load_test_result')->nullable();
            $table->text('load_test_notes')->nullable();

            // Re-add MPI Service fields
            $table->unsignedBigInteger('mpi_service_inspector')->nullable();
            $table->unsignedBigInteger('visual_inspector')->nullable();
            $table->text('visual_comments')->nullable();
            $table->string('visual_method')->nullable();
            $table->string('visual_lighting')->nullable();
            $table->string('visual_equipment')->nullable();
            $table->text('visual_conditions')->nullable();
            $table->text('visual_results')->nullable();

            // Re-add Lifting Examination fields
            $table->unsignedBigInteger('lifting_examination_inspector')->nullable();
            $table->unsignedBigInteger('thorough_examination_inspector')->nullable();
            $table->text('thorough_examination_comments')->nullable();
            $table->string('thorough_method')->nullable();
            $table->string('thorough_equipment')->nullable();
            $table->text('thorough_conditions')->nullable();
            $table->text('thorough_results')->nullable();

            // Re-add foreign key constraints
            $table->foreign('load_test_inspector')->references('id')->on('personnels')->onDelete('set null');
            $table->foreign('mpi_service_inspector')->references('id')->on('personnels')->onDelete('set null');
            $table->foreign('visual_inspector')->references('id')->on('personnels')->onDelete('set null');
            $table->foreign('lifting_examination_inspector')->references('id')->on('personnels')->onDelete('set null');
            $table->foreign('thorough_examination_inspector')->references('id')->on('personnels')->onDelete('set null');
        });
    }
};
