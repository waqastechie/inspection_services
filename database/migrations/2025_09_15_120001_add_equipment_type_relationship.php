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
        Schema::table('equipment_assignments', function (Blueprint $table) {
            // Add foreign key to equipment_types
            $table->foreignId('equipment_type_id')->nullable()->after('equipment_type')->constrained('equipment_types')->onDelete('set null');
            
            // Add index for better performance
            $table->index(['inspection_id', 'equipment_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_assignments', function (Blueprint $table) {
            $table->dropForeign(['equipment_type_id']);
            $table->dropIndex(['inspection_id', 'equipment_type_id']);
            $table->dropColumn('equipment_type_id');
        });
    }
};