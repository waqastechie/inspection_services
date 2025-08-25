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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            
            // Basic Client Information
            $table->string('client_name')->index();
            $table->string('client_code', 10)->unique()->nullable();
            $table->string('company_type')->nullable(); // LLC, Corporation, Partnership, etc.
            $table->string('industry')->nullable(); // Oil & Gas, Manufacturing, etc.
            
            // Primary Address
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('United States');
            $table->string('postal_code')->nullable();
            
            // Contact Information
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            // Primary Contact Person
            $table->string('contact_person')->nullable();
            $table->string('contact_position')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            
            // Billing Information
            $table->text('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_postal_code')->nullable();
            
            // Business Information
            $table->string('tax_id')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('payment_terms')->default('Net 30');
            $table->decimal('credit_limit', 10, 2)->nullable();
            $table->string('preferred_currency', 3)->default('USD');
            
            // Additional Information
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['client_name', 'is_active']);
            $table->index('email');
            $table->index('contact_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
