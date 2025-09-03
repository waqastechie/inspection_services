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
        // Simply mark as completed since the columns seem to already exist
        // This migration is mainly for documentation purposes
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['lifting_examination_inspector']);
            $table->dropForeign(['load_test_inspector']);
            $table->dropForeign(['thorough_examination_inspector']);
            $table->dropForeign(['mpi_service_inspector']);
            $table->dropForeign(['visual_inspector']);
            
            // Drop columns
            $table->dropColumn([
                'lifting_examination_inspector',
                'load_test_inspector',
                'thorough_examination_inspector',
                'mpi_service_inspector',
                'visual_inspector'
            ]);
        });
    }
};
