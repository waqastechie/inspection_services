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
        Schema::table('users', function (Blueprint $table) {
            // Use string for role instead of enum for flexibility
            $table->string('role', 32)->default('inspector')->change();
            $table->boolean('is_active')->default(true)->change();
            $table->string('phone')->nullable()->change();
            $table->string('department')->nullable()->change();
            $table->string('certification')->nullable()->change();
            $table->date('certification_expiry')->nullable()->change();
            $table->timestamp('last_login')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'is_active',
                'phone',
                'department',
                'certification',
                'certification_expiry',
                'last_login'
            ]);
        });
    }
};
