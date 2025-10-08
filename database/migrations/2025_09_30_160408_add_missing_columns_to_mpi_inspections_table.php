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
        Schema::table('mpi_inspections', function (Blueprint $table) {
            // Add missing columns from the newer migration
            $table->string('mpi_service_inspector')->nullable()->after('inspector_id');
            $table->string('visual_inspector')->nullable()->after('mpi_service_inspector');
            $table->text('visual_comments')->nullable()->after('visual_inspector');
            $table->string('visual_method')->nullable()->after('visual_comments');
            $table->string('visual_lighting')->nullable()->after('visual_method');
            $table->string('visual_equipment')->nullable()->after('visual_lighting');
            $table->string('visual_conditions')->nullable()->after('visual_equipment');
            $table->string('visual_results')->nullable()->after('visual_conditions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpi_inspections', function (Blueprint $table) {
            $table->dropColumn([
                'mpi_service_inspector',
                'visual_inspector',
                'visual_comments',
                'visual_method',
                'visual_lighting',
                'visual_equipment',
                'visual_conditions',
                'visual_results'
            ]);
        });
    }
};
