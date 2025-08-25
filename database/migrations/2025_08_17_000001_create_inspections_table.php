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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('inspection_number')->unique();
            $table->string('client_name');
            $table->string('project_name');
            $table->string('location');
            $table->date('inspection_date');
            $table->string('weather_conditions')->nullable();
            $table->string('temperature')->nullable();
            $table->string('humidity')->nullable();
            
            // Equipment Under Test
            $table->string('equipment_type');
            $table->string('equipment_description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('asset_tag')->nullable();
            $table->year('manufacture_year')->nullable();
            $table->decimal('capacity', 10, 2)->nullable();
            $table->string('capacity_unit')->nullable();
            
            // Certification & Standards (service-conditional fields)
            $table->string('applicable_standard')->nullable();
            $table->string('inspection_class')->nullable();
            $table->string('certification_body')->nullable();
            $table->string('previous_certificate_number')->nullable();
            $table->date('last_inspection_date')->nullable();
            $table->date('next_inspection_due')->nullable();
            
            // Test Results
            $table->enum('overall_result', ['pass', 'fail', 'conditional_pass'])->default('pass');
            $table->text('defects_found')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('limitations')->nullable();
            
            // Inspector Information
            $table->string('lead_inspector_name');
            $table->string('lead_inspector_certification');
            $table->string('inspector_signature')->nullable();
            $table->date('report_date')->nullable();
            
            // Additional Notes
            $table->text('general_notes')->nullable();
            $table->json('attachments')->nullable(); // Store file paths as JSON
            
            // Status
            $table->enum('status', ['draft', 'in_progress', 'completed', 'approved'])->default('draft');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['client_name', 'project_name']);
            $table->index('inspection_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
