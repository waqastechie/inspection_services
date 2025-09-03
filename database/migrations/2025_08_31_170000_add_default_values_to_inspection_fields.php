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
            // Add default values to fields that might be missing them
            $table->string('equipment_description')->default('General Equipment')->change();
            $table->string('applicable_standard')->default('General Standard')->change();
            $table->string('inspection_class')->default('General')->change();
            $table->string('lead_inspector_name')->default('TBD')->change();
            $table->string('lead_inspector_certification')->nullable()->default('TBD')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            // Remove default values (make them required again)
            $table->string('equipment_description')->default(null)->change();
            $table->string('applicable_standard')->default(null)->change();
            $table->string('inspection_class')->default(null)->change();
            $table->string('lead_inspector_name')->default(null)->change();
            $table->string('lead_inspector_certification')->nullable()->default(null)->change();
        });
    }
};
