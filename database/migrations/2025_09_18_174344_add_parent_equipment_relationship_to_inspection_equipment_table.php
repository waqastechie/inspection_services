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
        Schema::table('inspection_equipment', function (Blueprint $table) {
            // Add parent equipment relationship
            $table->foreignId('parent_equipment_id')->nullable()->after('equipment_type_id')
                  ->constrained('inspection_equipment')->onDelete('cascade');
            
            // Add an index for better performance when querying parent-child relationships
            $table->index(['parent_equipment_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_equipment', function (Blueprint $table) {
            $table->dropForeign(['parent_equipment_id']);
            $table->dropIndex(['parent_equipment_id', 'category']);
            $table->dropColumn('parent_equipment_id');
        });
    }
};
