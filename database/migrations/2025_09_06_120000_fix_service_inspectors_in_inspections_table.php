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
        // Add service inspector columns if they don't exist
        if (!Schema::hasColumn('inspections', 'lifting_examination_inspector')) {
            Schema::table('inspections', function (Blueprint $table) {
                $table->unsignedBigInteger('lifting_examination_inspector')->nullable()->after('inspector_signature');
                $table->unsignedBigInteger('load_test_inspector')->nullable()->after('lifting_examination_inspector');
                $table->unsignedBigInteger('thorough_examination_inspector')->nullable()->after('load_test_inspector');
                $table->unsignedBigInteger('mpi_service_inspector')->nullable()->after('thorough_examination_inspector');
                $table->unsignedBigInteger('visual_inspector')->nullable()->after('mpi_service_inspector');
                
                // Add foreign key constraints
                $table->foreign('lifting_examination_inspector')->references('id')->on('personnels')->onDelete('set null');
                $table->foreign('load_test_inspector')->references('id')->on('personnels')->onDelete('set null');
                $table->foreign('thorough_examination_inspector')->references('id')->on('personnels')->onDelete('set null');
                $table->foreign('mpi_service_inspector')->references('id')->on('personnels')->onDelete('set null');
                $table->foreign('visual_inspector')->references('id')->on('personnels')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            if (Schema::hasColumn('inspections', 'lifting_examination_inspector')) {
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
            }
        });
    }
};
