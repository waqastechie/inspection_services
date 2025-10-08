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
            if (!Schema::hasColumn('inspections', 'load_test_duration')) {
                $table->string('load_test_duration')->nullable()->after('load_test_inspector');
            }
            if (!Schema::hasColumn('inspections', 'load_test_two_points_diagonal')) {
                $table->string('load_test_two_points_diagonal')->nullable()->after('load_test_duration');
            }
            if (!Schema::hasColumn('inspections', 'load_test_four_points')) {
                $table->string('load_test_four_points')->nullable()->after('load_test_two_points_diagonal');
            }
            if (!Schema::hasColumn('inspections', 'load_test_deflection')) {
                $table->string('load_test_deflection')->nullable()->after('load_test_four_points');
            }
            if (!Schema::hasColumn('inspections', 'load_test_deformation')) {
                $table->string('load_test_deformation')->nullable()->after('load_test_deflection');
            }
            if (!Schema::hasColumn('inspections', 'load_test_distance_from_ground')) {
                $table->string('load_test_distance_from_ground')->nullable()->after('load_test_deformation');
            }
            if (!Schema::hasColumn('inspections', 'load_test_result')) {
                $table->string('load_test_result')->nullable()->after('load_test_distance_from_ground');
            }
            if (!Schema::hasColumn('inspections', 'load_test_notes')) {
                $table->text('load_test_notes')->nullable()->after('load_test_result');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $columns = [
                'load_test_duration',
                'load_test_two_points_diagonal',
                'load_test_four_points',
                'load_test_deflection',
                'load_test_deformation',
                'load_test_distance_from_ground',
                'load_test_result',
                'load_test_notes',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('inspections', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
