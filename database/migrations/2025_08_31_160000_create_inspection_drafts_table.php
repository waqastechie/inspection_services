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
        Schema::create('inspection_drafts', function (Blueprint $table) {
            $table->id();
            $table->string('draft_id')->unique(); // Unique identifier for frontend
            $table->longText('form_data')->nullable(); // Basic form fields
            $table->json('selected_services')->nullable(); // Selected services array
            $table->json('personnel_assignments')->nullable(); // Personnel modal data
            $table->json('equipment_assignments')->nullable(); // Equipment modal data
            $table->json('consumable_assignments')->nullable(); // Consumable modal data
            $table->json('uploaded_images')->nullable(); // Image data
            $table->json('service_sections_data')->nullable(); // MPI and other service section data
            $table->string('user_session')->nullable(); // Session identifier
            $table->string('ip_address')->nullable(); // User IP for identification
            $table->timestamp('last_saved_at')->nullable();
            $table->boolean('is_submitted')->default(false);
            $table->integer('version')->default(1); // Version control for updates
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['draft_id', 'user_session']);
            $table->index('last_saved_at');
            $table->index('is_submitted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_drafts');
    }
};
