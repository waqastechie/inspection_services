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
            // Add missing columns from the wizard form
            if (!Schema::hasColumn('inspections', 'rig')) {
                $table->string('rig')->nullable()->after('client_job_reference');
            }
            if (!Schema::hasColumn('inspections', 'contract')) {
                $table->string('contract')->nullable()->after('location');
            }
            if (!Schema::hasColumn('inspections', 'work_order')) {
                $table->string('work_order')->nullable()->after('contract');
            }
            if (!Schema::hasColumn('inspections', 'purchase_order')) {
                $table->string('purchase_order')->nullable()->after('work_order');
            }
            if (!Schema::hasColumn('inspections', 'client_job_reference')) {
                $table->string('client_job_reference')->nullable()->after('purchase_order');
            }
            if (!Schema::hasColumn('inspections', 'report_number')) {
                $table->string('report_number')->nullable()->after('rig');
            }
            if (!Schema::hasColumn('inspections', 'revision')) {
                $table->string('revision')->nullable()->after('report_number');
            }
            if (!Schema::hasColumn('inspections', 'area_of_examination')) {
                $table->string('area_of_examination')->nullable()->after('revision');
            }
            if (!Schema::hasColumn('inspections', 'standards')) {
                $table->string('standards')->nullable()->after('area_of_examination');
            }
            if (!Schema::hasColumn('inspections', 'local_procedure_number')) {
                $table->string('local_procedure_number')->nullable()->after('standards');
            }
            if (!Schema::hasColumn('inspections', 'drawing_number')) {
                $table->string('drawing_number')->nullable()->after('local_procedure_number');
            }
            if (!Schema::hasColumn('inspections', 'test_restrictions')) {
                $table->text('test_restrictions')->nullable()->after('drawing_number');
            }
            if (!Schema::hasColumn('inspections', 'surface_condition')) {
                $table->string('surface_condition')->nullable()->after('test_restrictions');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $columns = [
                'rig', 'contract', 'work_order', 'purchase_order', 'client_job_reference',
                'report_number', 'revision', 'area_of_examination', 'standards',
                'local_procedure_number', 'drawing_number', 'test_restrictions', 'surface_condition'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('inspections', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};