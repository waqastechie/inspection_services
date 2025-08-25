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
        Schema::create('consumables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // e.g., 'chemicals', 'materials', 'supplies'
            $table->string('brand_manufacturer')->nullable();
            $table->string('product_code')->nullable();
            $table->string('batch_lot_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity_available', 10, 2)->default(0);
            $table->string('unit')->nullable(); // e.g., 'kg', 'liters', 'pieces'
            $table->decimal('unit_cost', 8, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->enum('condition', ['new', 'good', 'expired', 'damaged'])->default('new');
            $table->text('storage_requirements')->nullable();
            $table->text('safety_notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('type');
            $table->index('condition');
            $table->index('is_active');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumables');
    }
};
