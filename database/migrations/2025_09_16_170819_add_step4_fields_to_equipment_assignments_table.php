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
        Schema::table('equipment_assignments', function (Blueprint $table) {
            // Add fields for equipment category
            $table->string('equipment_category')->nullable()->after('equipment_type'); // 'asset' or 'item'
            
            // Add fields for detailed equipment information
            $table->text('description')->nullable()->after('equipment_name');
            $table->string('reason_for_examination')->nullable()->after('description');
            
            // Add fields for inspection items (SWL, test loads, etc.)
            $table->decimal('swl', 10, 2)->nullable()->after('reason_for_examination'); // Safe Working Load
            $table->decimal('test_load_applied', 10, 2)->nullable()->after('swl'); // Test Load Applied
            $table->date('date_of_manufacture')->nullable()->after('test_load_applied');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_assignments', function (Blueprint $table) {
            $table->dropColumn([
                'equipment_category',
                'description',
                'reason_for_examination',
                'swl',
                'test_load_applied',
                'date_of_manufacture'
            ]);
        });
    }
};
