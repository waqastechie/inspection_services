<?php
// Migration intentionally disabled to avoid foreign key constraint errors.
// Entire file commented out to prevent syntax errors.

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
        // Drop the inspection_services table since we're using direct relationships
        Schema::dropIfExists('inspection_services');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the inspection_services table if needed (rollback)
        Schema::create('inspection_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->string('service_type');
            $table->string('service_name')->nullable();
            $table->text('service_description')->nullable();
            $table->string('equipment_type')->nullable();
            $table->text('equipment_description')->nullable();
            $table->string('test_method')->nullable();
            $table->text('acceptance_criteria')->nullable();
            $table->decimal('test_load', 10, 2)->nullable();
            $table->string('test_load_unit')->nullable();
            $table->decimal('safe_working_load', 10, 2)->nullable();
            $table->string('swl_unit')->nullable();
            $table->text('notes')->nullable();
            $table->json('service_data')->nullable();
            $table->timestamps();
        });
    }
};

