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
            // Drop Test fields
            $table->string('drop_test_load')->nullable()->after('thorough_results');
            $table->string('drop_type')->nullable()->after('drop_test_load');
            $table->string('drop_distance')->nullable()->after('drop_type');
            $table->string('drop_suspended')->nullable()->after('drop_distance');
            $table->string('drop_impact_speed')->nullable()->after('drop_suspended');
            $table->string('drop_result')->nullable()->after('drop_impact_speed');
            $table->text('drop_notes')->nullable()->after('drop_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn([
                'drop_test_load',
                'drop_type',
                'drop_distance',
                'drop_suspended',
                'drop_impact_speed',
                'drop_result',
                'drop_notes'
            ]);
        });
    }
};