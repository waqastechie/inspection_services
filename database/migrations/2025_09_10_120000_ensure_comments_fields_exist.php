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
            // Check if the columns don't exist before adding them
            if (!Schema::hasColumn('inspections', 'inspector_comments')) {
                $table->text('inspector_comments')->nullable()->after('general_notes');
            }
            if (!Schema::hasColumn('inspections', 'next_inspection_date')) {
                $table->date('next_inspection_date')->nullable()->after('next_inspection_due');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            if (Schema::hasColumn('inspections', 'inspector_comments')) {
                $table->dropColumn('inspector_comments');
            }
            if (Schema::hasColumn('inspections', 'next_inspection_date')) {
                $table->dropColumn('next_inspection_date');
            }
        });
    }
};
