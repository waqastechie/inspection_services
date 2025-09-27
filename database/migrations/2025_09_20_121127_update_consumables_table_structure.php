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
        Schema::table('consumables', function (Blueprint $table) {
            // Drop columns that don't match the user's requirements
            $table->dropColumn([
                'name',
                'brand_manufacturer', 
                'product_code',
                'quantity_available',
                'unit_cost',
                'supplier',
                'storage_requirements',
                'safety_notes'
            ]);
            
            // Rename and modify existing columns to match requirements
            $table->renameColumn('batch_lot_number', 'batch_number');
            
            // Add new columns as per user's screenshot
            $table->string('manufacturer')->after('type');
            $table->text('description')->after('manufacturer');
            $table->json('services')->nullable()->after('expiry_date')->comment('JSON array of applicable services');
            
            // Modify existing columns
            $table->string('type')->change(); // Ensure it's a string
            $table->date('expiry_date')->nullable()->change();
            $table->string('batch_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consumables', function (Blueprint $table) {
            // Restore original columns
            $table->string('name')->after('id');
            $table->string('brand_manufacturer')->nullable();
            $table->string('product_code')->nullable();
            $table->integer('quantity_available')->default(0);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->text('storage_requirements')->nullable();
            $table->text('safety_notes')->nullable();
            
            // Remove new columns
            $table->dropColumn(['manufacturer', 'description', 'services']);
            
            // Rename back
            $table->renameColumn('batch_number', 'batch_lot_number');
        });
    }
};
