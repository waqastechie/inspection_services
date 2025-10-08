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
        Schema::table('lifting_examinations', function (Blueprint $table) {
            // Add missing columns that the application expects
            $table->string('lifting_examination_inspector')->nullable()->after('inspector_id');
            $table->string('thorough_examination_inspector')->nullable()->after('lifting_examination_inspector');
            $table->text('thorough_examination_comments')->nullable()->after('thorough_examination_inspector');
            $table->string('thorough_method')->nullable()->after('thorough_examination_comments');
            $table->string('thorough_equipment')->nullable()->after('thorough_method');
            $table->string('thorough_conditions')->nullable()->after('thorough_equipment');
            $table->string('thorough_results')->nullable()->after('thorough_conditions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lifting_examinations', function (Blueprint $table) {
            $table->dropColumn([
                'lifting_examination_inspector',
                'thorough_examination_inspector',
                'thorough_examination_comments',
                'thorough_method',
                'thorough_equipment',
                'thorough_conditions',
                'thorough_results'
            ]);
        });
    }
};
