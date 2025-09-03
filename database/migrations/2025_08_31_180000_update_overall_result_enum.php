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
        // Update the overall_result enum to include more options
        DB::statement("ALTER TABLE inspections MODIFY COLUMN overall_result ENUM('pass', 'fail', 'conditional_pass', 'satisfactory', 'unsatisfactory', 'acceptable', 'unacceptable', 'retest_required') DEFAULT 'pass'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE inspections MODIFY COLUMN overall_result ENUM('pass', 'fail', 'conditional_pass') DEFAULT 'pass'");
    }
};
