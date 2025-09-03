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
        Schema::table('inspections', function (Blueprint $table) {
            // Drop the enum constraint and recreate it with more options
            $table->string('overall_result_temp')->nullable();
        });
        
        // Copy existing data
        \DB::table('inspections')->update([
            'overall_result_temp' => \DB::raw('overall_result')
        ]);
        
        Schema::table('inspections', function (Blueprint $table) {
            // Drop the old column
            $table->dropColumn('overall_result');
        });
        
        Schema::table('inspections', function (Blueprint $table) {
            // Add the new enum column with expanded options
            $table->enum('overall_result', [
                'pass', 
                'fail', 
                'conditional_pass', 
                'satisfactory', 
                'unsatisfactory', 
                'acceptable', 
                'unacceptable', 
                'retest_required'
            ])->default('pass');
        });
        
        // Copy data back
        \DB::table('inspections')->update([
            'overall_result' => \DB::raw('overall_result_temp')
        ]);
        
        Schema::table('inspections', function (Blueprint $table) {
            // Drop the temporary column
            $table->dropColumn('overall_result_temp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->string('overall_result_temp')->nullable();
        });
        
        // Copy existing data
        \DB::table('inspections')->update([
            'overall_result_temp' => \DB::raw('overall_result')
        ]);
        
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn('overall_result');
        });
        
        Schema::table('inspections', function (Blueprint $table) {
            // Revert to original enum
            $table->enum('overall_result', ['pass', 'fail', 'conditional_pass'])->default('pass');
        });
        
        // Copy data back (only valid values)
        \DB::table('inspections')
            ->whereIn('overall_result_temp', ['pass', 'fail', 'conditional_pass'])
            ->update([
                'overall_result' => \DB::raw('overall_result_temp')
            ]);
        
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn('overall_result_temp');
        });
    }
};
