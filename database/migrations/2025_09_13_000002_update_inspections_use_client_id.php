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
            // Add client_id foreign key column
            $table->unsignedBigInteger('client_id')->nullable()->after('inspection_number');
            
            // Add foreign key constraint
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            
            // Remove client_name column if it exists
            if (Schema::hasColumn('inspections', 'client_name')) {
                $table->dropColumn('client_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['client_id']);
            
            // Drop client_id column
            $table->dropColumn('client_id');
            
            // Add back client_name column
            $table->string('client_name')->after('inspection_number');
        });
    }
};
