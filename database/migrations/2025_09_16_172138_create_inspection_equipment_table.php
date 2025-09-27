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
        Schema::create('inspection_equipment', function (Blueprint $table) {
            $table->id();
            
            // Foreign key references
            $table->foreignId('inspection_id')->constrained('inspections')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('equipment_type_id')->nullable()->constrained('equipment_types')->onDelete('set null');
            
            // Equipment category to distinguish between assets and items
            $table->enum('category', ['asset', 'item'])->default('asset');
            
            // Common fields for both assets and items
            $table->string('equipment_type')->nullable(); // Gas Rack, Offshore Container, etc.
            $table->string('serial_number');
            $table->text('description');
            $table->string('reason_for_examination');
            $table->string('model')->nullable();
            
            // Fields specific to items (inspection items)
            $table->decimal('swl', 10, 2)->nullable(); // Safe Working Load
            $table->decimal('test_load_applied', 10, 2)->nullable(); // Test Load Applied
            $table->date('date_of_manufacture')->nullable();
            $table->date('date_of_last_examination')->nullable();
            $table->date('date_of_next_examination')->nullable();
            $table->string('status')->nullable(); // ND, D, NI, R
            $table->text('remarks')->nullable();
            
            // Additional tracking fields
            $table->string('condition')->default('good');
            $table->json('metadata')->nullable(); // For any additional data
            
            $table->timestamps();
            
            // Indexes
            $table->index(['inspection_id', 'category']);
            $table->index('serial_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_equipment');
    }
};
