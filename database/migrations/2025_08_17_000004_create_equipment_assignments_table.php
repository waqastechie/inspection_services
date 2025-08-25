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
        Schema::create('equipment_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->string('equipment_id'); // Reference to equipment in system
            $table->string('equipment_name');
            $table->string('equipment_type'); // ut_flaw_detector, mt_yoke, etc.
            $table->string('make_model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('condition'); // excellent, good, fair, poor
            $table->string('calibration_status'); // current, due_soon, overdue, not_required
            $table->date('last_calibration_date')->nullable();
            $table->date('next_calibration_date')->nullable();
            $table->string('calibration_certificate')->nullable();
            $table->json('assigned_services'); // Array of services this equipment is used for
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['inspection_id', 'equipment_type']);
            $table->index('equipment_id');
            $table->index('calibration_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_assignments');
    }
};
