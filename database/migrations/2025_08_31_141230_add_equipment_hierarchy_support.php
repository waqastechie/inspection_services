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
        // Add fields to support equipment hierarchy
        Schema::table('equipment', function (Blueprint $table) {
            $table->foreignId('parent_equipment_id')->nullable()->constrained('equipment')->onDelete('cascade');
            $table->string('equipment_category')->default('asset'); // 'asset' or 'item'
            $table->string('swl')->nullable(); // Safe Working Load
            $table->string('test_load_applied')->nullable();
            $table->string('reason_for_examination')->nullable();
            $table->date('manufacture_date')->nullable();
            $table->date('last_examination_date')->nullable();
            $table->date('next_examination_date')->nullable();
            $table->string('examination_status')->default('ND'); // ND, Pass, Fail, etc.
            $table->text('examination_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropForeign(['parent_equipment_id']);
            $table->dropColumn([
                'parent_equipment_id',
                'equipment_category',
                'swl',
                'test_load_applied',
                'reason_for_examination',
                'manufacture_date',
                'last_examination_date',
                'next_examination_date',
                'examination_status',
                'examination_notes'
            ]);
        });
    }
};
