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
            
            // Lifting Examination specific fields from inspections table
            $table->string('lifting_examination_inspector')->nullable();
            $table->string('thorough_examination_inspector')->nullable();
            $table->text('thorough_examination_comments')->nullable();
            $table->string('thorough_method')->nullable();
            $table->string('thorough_equipment')->nullable();
            $table->string('thorough_conditions')->nullable();
            $table->string('thorough_results')->nullable();
            
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
