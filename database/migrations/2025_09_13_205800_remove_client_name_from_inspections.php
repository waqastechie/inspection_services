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
            // Remove client_name column if it exists
            if (Schema::hasColumn('inspections', 'client_name')) {
                $table->dropColumn('client_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            // Add back client_name column for rollback
            $table->string('client_name')->nullable()->after('inspection_number');
        });
    }
};
