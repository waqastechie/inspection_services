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
        Schema::create('lifting_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            // Remove inspection_service_id since we don't need the intermediate table
            
            // Inspector assignment (matches HTML form field: lifting_examination_inspector)
            $table->foreignId('inspector_id')->nullable()->constrained('personnels')->onDelete('set null');
            
            // Form fields from lifting-examination.blade.php
            $table->enum('first_examination', ['yes', 'no'])->nullable();
            $table->text('equipment_installation_details')->nullable();
            $table->enum('safe_to_operate', ['yes', 'no'])->nullable();
            $table->enum('equipment_defect', ['yes', 'no'])->nullable();
            $table->text('defect_description')->nullable();
            $table->text('existing_danger')->nullable();
            $table->text('potential_danger')->nullable();
            $table->date('defect_timeline')->nullable();
            $table->text('repair_details')->nullable();
            $table->text('test_details')->nullable();
            
            $table->timestamps();
            
            $table->index('inspection_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lifting_examinations');
    }
};
