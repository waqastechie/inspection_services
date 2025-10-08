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
        Schema::table('load_tests', function (Blueprint $table) {
            // Add legacy columns for backward compatibility with controller code
            $table->string('duration_held')->nullable()->after('load_test_notes');
            $table->string('two_points_diagonal')->nullable()->after('duration_held');
            $table->string('four_points')->nullable()->after('two_points_diagonal');
            $table->string('deflection')->nullable()->after('four_points');
            $table->string('deformation')->nullable()->after('deflection');
            $table->string('distance_from_ground')->nullable()->after('deformation');
            $table->string('result')->nullable()->after('distance_from_ground');
            $table->string('test_load')->nullable()->after('result');
            $table->string('safe_working_load')->nullable()->after('test_load');
            $table->string('test_method')->nullable()->after('safe_working_load');
            $table->string('test_equipment')->nullable()->after('test_method');
            $table->string('test_conditions')->nullable()->after('test_equipment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('load_tests', function (Blueprint $table) {
            $table->dropColumn([
                'duration_held',
                'two_points_diagonal',
                'four_points',
                'deflection',
                'deformation',
                'distance_from_ground',
                'result',
                'test_load',
                'safe_working_load',
                'test_method',
                'test_equipment',
                'test_conditions'
            ]);
        });
    }
};
