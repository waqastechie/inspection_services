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
        // Add additional columns to inspection_services table for better relationships
        Schema::table('inspection_services', function (Blueprint $table) {
            $table->string('service_name')->nullable()->after('service_type');
            $table->text('service_description')->nullable()->after('service_name');
            $table->decimal('estimated_cost', 10, 2)->nullable()->after('service_description');
            $table->integer('estimated_duration_minutes')->nullable()->after('estimated_cost');
            $table->json('test_parameters')->nullable()->after('estimated_duration_minutes');
            $table->json('acceptance_criteria')->nullable()->after('test_parameters');
            $table->string('applicable_standard')->nullable()->after('acceptance_criteria');
            $table->datetime('scheduled_date')->nullable()->after('applicable_standard');
            $table->datetime('completed_date')->nullable()->after('scheduled_date');
            $table->foreignId('assigned_inspector_id')->nullable()->constrained('personnels')->onDelete('set null')->after('completed_date');
        });

        // Create pivot table for service-equipment relationships
        Schema::create('service_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_service_id')->constrained('inspection_services')->onDelete('cascade');
            $table->foreignId('equipment_assignment_id')->constrained('equipment_assignments')->onDelete('cascade');
            $table->json('specific_test_data')->nullable(); // Equipment-specific test data
            $table->enum('equipment_status', ['pass', 'fail', 'not_tested', 'conditional'])->default('not_tested');
            $table->text('equipment_notes')->nullable();
            $table->timestamps();
            
            $table->unique(['inspection_service_id', 'equipment_assignment_id'], 'service_equipment_unique');
        });

        // Create pivot table for service-personnel relationships
        Schema::create('service_personnel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_service_id')->constrained('inspection_services')->onDelete('cascade');
            $table->foreignId('personnel_assignment_id')->constrained('personnel_assignments')->onDelete('cascade');
            $table->string('role_in_service')->nullable(); // e.g., 'primary_inspector', 'assistant', 'witness'
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->timestamps();
            
            $table->unique(['inspection_service_id', 'personnel_assignment_id'], 'service_personnel_unique');
        });

        // Create pivot table for service-consumable relationships
        Schema::create('service_consumables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_service_id')->constrained('inspection_services')->onDelete('cascade');
            $table->foreignId('consumable_assignment_id')->constrained('consumable_assignments')->onDelete('cascade');
            $table->decimal('quantity_used', 8, 2)->nullable();
            $table->string('usage_notes')->nullable();
            $table->timestamps();
            
            $table->unique(['inspection_service_id', 'consumable_assignment_id'], 'service_consumables_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop pivot tables
        Schema::dropIfExists('service_consumables');
        Schema::dropIfExists('service_personnel');
        Schema::dropIfExists('service_equipment');
        
        // Remove columns from inspection_services table
        Schema::table('inspection_services', function (Blueprint $table) {
            $table->dropForeign(['assigned_inspector_id']);
            $table->dropColumn([
                'service_name',
                'service_description', 
                'estimated_cost',
                'estimated_duration_minutes',
                'test_parameters',
                'acceptance_criteria',
                'applicable_standard',
                'scheduled_date',
                'completed_date',
                'assigned_inspector_id'
            ]);
        });
    }
};
