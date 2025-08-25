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
        Schema::create('inspection_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->foreignId('inspection_service_id')->constrained()->onDelete('cascade');
            $table->string('test_location')->nullable(); // Specific location on equipment
            $table->string('test_method')->nullable();
            $table->json('test_parameters')->nullable(); // Service-specific parameters
            $table->json('measurements')->nullable(); // Actual measurements/readings
            $table->enum('result', ['pass', 'fail', 'acceptable', 'reject'])->default('pass');
            $table->text('observations')->nullable();
            $table->text('defects_noted')->nullable();
            $table->string('acceptance_criteria')->nullable();
            $table->json('images')->nullable(); // Array of image file paths
            $table->json('documents')->nullable(); // Array of document file paths
            $table->string('inspector_name')->nullable();
            $table->datetime('test_datetime')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
            
            $table->index(['inspection_id', 'result']);
            $table->index('inspection_service_id');
            $table->index('test_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_results');
    }
};
