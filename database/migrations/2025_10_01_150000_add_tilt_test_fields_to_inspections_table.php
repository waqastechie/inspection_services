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
            // Tilt Test fields
            $table->string('tilt_test_load')->nullable()->after('drop_notes');
            $table->string('loaded_tilt')->nullable()->after('tilt_test_load');
            $table->string('empty_tilt')->nullable()->after('loaded_tilt');
            $table->string('tilt_results')->nullable()->after('empty_tilt');
            $table->string('tilt_stability')->nullable()->after('tilt_results');
            $table->string('tilt_direction')->nullable()->after('tilt_stability');
            $table->integer('tilt_duration')->nullable()->after('tilt_direction');
            $table->text('tilt_notes')->nullable()->after('tilt_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn([
                'tilt_test_load',
                'loaded_tilt',
                'empty_tilt',
                'tilt_results',
                'tilt_stability',
                'tilt_direction',
                'tilt_duration',
                'tilt_notes'
            ]);
        });
    }
};