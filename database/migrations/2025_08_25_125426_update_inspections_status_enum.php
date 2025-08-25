<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing 'approved' status to 'completed' since they are similar
        DB::table('inspections')->where('status', 'approved')->update(['status' => 'completed']);
        
        // For MySQL, we need to alter the enum column
        DB::statement("ALTER TABLE inspections MODIFY COLUMN status ENUM('draft', 'in_progress', 'completed', 'cancelled') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert any 'cancelled' back to 'completed' to avoid constraint violations
        DB::table('inspections')->where('status', 'cancelled')->update(['status' => 'completed']);
        
        // Revert back to original enum
        DB::statement("ALTER TABLE inspections MODIFY COLUMN status ENUM('draft', 'in_progress', 'completed', 'approved') DEFAULT 'draft'");
    }
};
