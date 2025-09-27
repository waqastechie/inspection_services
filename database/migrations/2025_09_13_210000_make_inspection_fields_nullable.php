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
            // Add client_id column if it doesn't exist
            if (!Schema::hasColumn('inspections', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('inspection_number');
                $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            }
            
            // Make fields nullable to prevent database errors
            $table->string('project_name')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->string('lead_inspector_name')->nullable()->change();
            $table->date('inspection_date')->nullable()->change();
        });
        
        // Update any records with null inspection_date to current date
        \DB::table('inspections')
            ->whereNull('inspection_date')
            ->update(['inspection_date' => now()->format('Y-m-d')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            // Remove foreign key and client_id column
            if (Schema::hasColumn('inspections', 'client_id')) {
                $table->dropForeign(['client_id']);
                $table->dropColumn('client_id');
            }
            
            // Revert to required fields (with caution)
            $table->string('project_name')->nullable(false)->change();
            $table->string('location')->nullable(false)->change();
            $table->string('lead_inspector_name')->nullable(false)->change();
            $table->date('inspection_date')->nullable(false)->change();
        });
    }
};
