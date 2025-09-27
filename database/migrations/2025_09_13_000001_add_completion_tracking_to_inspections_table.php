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
            if (!Schema::hasColumn('inspections', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('status');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('inspections', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('inspections', 'completed_by')) {
                $table->unsignedBigInteger('completed_by')->nullable()->after('status');
                $table->foreign('completed_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['completed_by']);
            $table->dropColumn(['created_by', 'completed_at', 'completed_by']);
        });
    }
};
