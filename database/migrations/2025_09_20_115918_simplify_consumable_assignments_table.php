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
        Schema::table('consumable_assignments', function (Blueprint $table) {
            // Remove unnecessary fields - these can be retrieved from the consumables table via consumable_id
            $table->dropColumn([
                'consumable_type',
                'brand_manufacturer', 
                'product_code',
                'expiry_date',
                'unit_cost',
                'total_cost',
                'supplier',
                'assigned_services'
            ]);
            
            // Modify consumable_id to be integer foreign key instead of string
            $table->dropColumn('consumable_id');
        });
        
        // Add the foreign key constraint in a separate statement
        Schema::table('consumable_assignments', function (Blueprint $table) {
            $table->foreignId('consumable_id')->after('inspection_id')->constrained('consumables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consumable_assignments', function (Blueprint $table) {
            // Reverse the changes - add back the removed fields
            $table->dropForeign(['consumable_id']);
            $table->dropColumn('consumable_id');
            
            $table->string('consumable_id')->after('inspection_id'); // Back to string
            $table->string('consumable_type')->after('consumable_name');
            $table->string('brand_manufacturer')->nullable()->after('consumable_type');
            $table->string('product_code')->nullable()->after('brand_manufacturer');
            $table->date('expiry_date')->nullable()->after('batch_lot_number');
            $table->decimal('unit_cost', 10, 2)->nullable()->after('unit');
            $table->decimal('total_cost', 10, 2)->nullable()->after('unit_cost');
            $table->string('supplier')->nullable()->after('total_cost');
            $table->json('assigned_services')->after('condition');
        });
    }
};
