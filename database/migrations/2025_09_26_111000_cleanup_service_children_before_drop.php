<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete all child records referencing inspection_services
        DB::statement('DELETE FROM service_consumables');
        DB::statement('DELETE FROM service_personnel');
        DB::statement('DELETE FROM service_equipment');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed for rollback
    }
};
