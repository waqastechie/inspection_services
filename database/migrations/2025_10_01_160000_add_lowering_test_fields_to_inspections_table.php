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
            // Lowering Test fields
            $table->string('lowering_test_load')->nullable()->after('tilt_notes');
            $table->string('lowering_impact_speed')->nullable()->after('lowering_test_load');
            $table->string('lowering_result')->nullable()->after('lowering_impact_speed');
            $table->string('lowering_method')->nullable()->after('lowering_result');
            $table->decimal('lowering_distance', 8, 2)->nullable()->after('lowering_method');
            $table->integer('lowering_duration')->nullable()->after('lowering_distance');
            $table->integer('lowering_cycles')->nullable()->after('lowering_duration');
            $table->string('brake_efficiency')->nullable()->after('lowering_cycles');
            $table->string('control_response')->nullable()->after('brake_efficiency');
            $table->text('lowering_notes')->nullable()->after('control_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn([
                'lowering_test_load',
                'lowering_impact_speed',
                'lowering_result',
                'lowering_method',
                'lowering_distance',
                'lowering_duration',
                'lowering_cycles',
                'brake_efficiency',
                'control_response',
                'lowering_notes'
            ]);
        });
    }
};