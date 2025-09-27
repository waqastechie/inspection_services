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
        Schema::table('inspection_services', function (Blueprint $table) {
            // Add normalized columns for better querying and form population
            if (!Schema::hasColumn('inspection_services', 'service_name')) {
                $table->string('service_name')->after('service_type')->nullable();
            }
            if (!Schema::hasColumn('inspection_services', 'service_description')) {
                $table->text('service_description')->after('service_name')->nullable();
            }
            
            // Additional commonly used fields that can be normalized
            if (!Schema::hasColumn('inspection_services', 'equipment_type')) {
                $table->string('equipment_type')->after('service_description')->nullable();
            }
            if (!Schema::hasColumn('inspection_services', 'equipment_description')) {
                $table->text('equipment_description')->after('equipment_type')->nullable();
            }
            if (!Schema::hasColumn('inspection_services', 'test_method')) {
                $table->string('test_method')->after('equipment_description')->nullable();
            }
            if (!Schema::hasColumn('inspection_services', 'acceptance_criteria')) {
                $table->string('acceptance_criteria')->after('test_method')->nullable();
            }
            if (!Schema::hasColumn('inspection_services', 'test_load')) {
                $table->decimal('test_load', 10, 2)->after('acceptance_criteria')->nullable();
            }
            if (!Schema::hasColumn('inspection_services', 'test_load_unit')) {
                $table->string('test_load_unit')->after('test_load')->nullable();
            }
            if (!Schema::hasColumn('inspection_services', 'safe_working_load')) {
                $table->decimal('safe_working_load', 10, 2)->after('test_load_unit')->nullable();
            }
            if (!Schema::hasColumn('inspection_services', 'swl_unit')) {
                $table->string('swl_unit')->after('safe_working_load')->nullable();
            }
            
            // Keep service_data for any additional custom fields
            // but now it will be used for service-specific extras only
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_services', function (Blueprint $table) {
            $table->dropColumn([
                'service_name',
                'service_description',
                'equipment_type',
                'equipment_description',
                'test_method',
                'acceptance_criteria',
                'test_load',
                'test_load_unit',
                'safe_working_load',
                'swl_unit'
            ]);
        });
    }
};
