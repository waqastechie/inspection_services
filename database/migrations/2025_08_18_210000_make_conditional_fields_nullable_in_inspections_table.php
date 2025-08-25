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
            // Make service-conditional fields nullable
            $table->string('applicable_standard')->nullable()->change();
            $table->string('inspection_class')->nullable()->change();
            
            // Also ensure report_date is nullable as it may not be set during drafts
            $table->date('report_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->string('applicable_standard')->nullable(false)->change();
            $table->string('inspection_class')->nullable(false)->change();
            $table->date('report_date')->nullable(false)->change();
        });
    }
};
