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
        Schema::create('personnel_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->string('personnel_id'); // Reference to personnel in system
            $table->string('personnel_name');
            $table->string('role'); // lead_inspector, assistant, witness, etc.
            $table->string('certification_level')->nullable();
            $table->string('certification_number')->nullable();
            $table->date('certification_expiry')->nullable();
            $table->json('assigned_services'); // Array of services assigned to this person
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['inspection_id', 'role']);
            $table->index('personnel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_assignments');
    }
};
