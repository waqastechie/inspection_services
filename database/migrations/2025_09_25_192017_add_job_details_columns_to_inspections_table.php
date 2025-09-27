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
            $table->string('rig')->nullable()->after('surface_condition');
            $table->string('report_number')->nullable()->after('rig');
            $table->string('revision')->nullable()->default('1.0')->after('report_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn(['rig', 'report_number', 'revision']);
        });
    }
};
