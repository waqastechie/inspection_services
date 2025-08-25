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
            $table->enum('role', ['super_admin', 'admin', 'inspector', 'viewer'])->default('inspector');
            $table->boolean('is_active')->default(true);
            $table->string('phone')->nullable();
            $table->string('department')->nullable();
            $table->string('certification')->nullable();
            $table->date('certification_expiry')->nullable();
            $table->timestamp('last_login')->nullable();
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
