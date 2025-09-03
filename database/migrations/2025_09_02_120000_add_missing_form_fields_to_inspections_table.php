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
            // Client Information fields that are missing
            $table->string('area_of_examination')->nullable()->after('location');
            $table->text('services_performed')->nullable()->after('area_of_examination');
            $table->string('contract')->nullable()->after('services_performed');
            $table->string('work_order')->nullable()->after('contract');
            $table->string('purchase_order')->nullable()->after('work_order');
            $table->string('client_job_reference')->nullable()->after('purchase_order');
            $table->string('standards')->nullable()->after('client_job_reference');
            $table->string('local_procedure_number')->nullable()->after('standards');
            $table->string('drawing_number')->nullable()->after('local_procedure_number');
            $table->text('test_restrictions')->nullable()->after('drawing_number');
            $table->text('surface_condition')->nullable()->after('test_restrictions');
            
            // Comments section fields
            $table->text('inspector_comments')->nullable()->after('general_notes');
            $table->date('next_inspection_date')->nullable()->after('next_inspection_due');
            
            // Additional missing fields for completeness
            $table->string('job_ref')->nullable()->after('client_job_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn([
                'area_of_examination',
                'services_performed', 
                'contract',
                'work_order',
                'purchase_order',
                'client_job_reference',
                'standards',
                'local_procedure_number',
                'drawing_number',
                'test_restrictions',
                'surface_condition',
                'inspector_comments',
                'next_inspection_date',
                'job_ref'
            ]);
        });
    }
};
