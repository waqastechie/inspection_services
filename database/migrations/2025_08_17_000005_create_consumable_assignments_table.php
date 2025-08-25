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
        Schema::create('consumable_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->string('consumable_id'); // Reference to consumable in system
            $table->string('consumable_name');
            $table->string('consumable_type'); // ut_couplant, pt_penetrant, etc.
            $table->string('brand_manufacturer')->nullable();
            $table->string('product_code')->nullable();
            $table->string('batch_lot_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity_used', 10, 3); // Quantity used
            $table->string('unit'); // pieces, liters, grams, etc.
            $table->decimal('unit_cost', 10, 2)->nullable(); // Cost per unit
            $table->decimal('total_cost', 10, 2)->nullable(); // Total cost
            $table->string('supplier')->nullable();
            $table->string('condition'); // new, good, acceptable, near_expiry
            $table->json('assigned_services'); // Array of services this consumable is used for
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['inspection_id', 'consumable_type']);
            $table->index('consumable_id');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumable_assignments');
    }
};
