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
        Schema::create('mpi_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            
            // MPI Service specific fields from inspections table
            $table->string('mpi_service_inspector')->nullable();
            $table->string('visual_inspector')->nullable();
            $table->text('visual_comments')->nullable();
            $table->string('visual_method')->nullable();
            $table->string('visual_lighting')->nullable();
            $table->string('visual_equipment')->nullable();
            $table->string('visual_conditions')->nullable();
            $table->string('visual_results')->nullable();
            
            $table->timestamps();
            
            $table->index('inspection_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpi_inspections');
    }
};
