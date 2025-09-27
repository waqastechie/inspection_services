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
            // MySQL does not support modifying ENUM directly, so use raw SQL
            \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'inspector', 'viewer', 'qa') DEFAULT 'inspector'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'inspector', 'viewer') DEFAULT 'inspector'");
        });
    }
};
