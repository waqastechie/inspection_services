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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role_position');
            $table->string('certification_level')->nullable();
            $table->string('certification_number')->nullable();
            $table->date('certification_expiry')->nullable();
            $table->string('contact_information')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->text('specialties')->nullable(); // JSON field for specialties
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('role_position');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};
