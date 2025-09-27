<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('inspection_services') && Schema::hasColumn('inspection_services', 'status')) {
            Schema::table('inspection_services', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('inspection_services') && !Schema::hasColumn('inspection_services', 'status')) {
            Schema::table('inspection_services', function (Blueprint $table) {
                $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            });
        }
    }
};
