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
            if (!Schema::hasColumn('consumables', 'manufacturer')) {
                $table->string('manufacturer')->nullable()->after('type');
            }
            if (!Schema::hasColumn('consumables', 'batch_number')) {
                $table->string('batch_number')->nullable()->after('description');
            }
            if (!Schema::hasColumn('consumables', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('batch_number');
            }
            if (!Schema::hasColumn('consumables', 'services')) {
                $table->string('services')->nullable()->after('expiry_date'); // can be CSV or JSON
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consumables', function (Blueprint $table) {
            if (Schema::hasColumn('consumables', 'manufacturer')) {
                $table->dropColumn('manufacturer');
            }
            if (Schema::hasColumn('consumables', 'batch_number')) {
                $table->dropColumn('batch_number');
            }
            if (Schema::hasColumn('consumables', 'expiry_date')) {
                $table->dropColumn('expiry_date');
            }
            if (Schema::hasColumn('consumables', 'services')) {
                $table->dropColumn('services');
            }
        });
    }
};
