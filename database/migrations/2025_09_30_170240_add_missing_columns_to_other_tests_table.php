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
        Schema::table('other_tests', function (Blueprint $table) {
            // Add missing columns for other tests service section
            $table->string('other_test_inspector')->nullable();
            $table->string('other_test_method')->nullable();
            $table->string('other_test_equipment')->nullable();
            $table->string('other_test_conditions')->nullable();
            $table->string('other_test_results')->nullable();
            $table->text('other_test_comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_tests', function (Blueprint $table) {
            $table->dropColumn([
                'other_test_inspector',
                'other_test_method',
                'other_test_equipment',
                'other_test_conditions',
                'other_test_results',
                'other_test_comments'
            ]);
        });
    }
};
