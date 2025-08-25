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
        Schema::table('personnels', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn([
                'name', 
                'role_position', 
                'certification_level', 
                'certification_number', 
                'certification_expiry',
                'contact_information',
                'specialties'
            ]);
            
            // Add new columns
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('position')->after('last_name');
            $table->string('department')->after('position');
            $table->string('employee_id')->nullable()->after('department');
            $table->string('supervisor')->nullable()->after('employee_id');
            $table->date('hire_date')->nullable()->after('supervisor');
            $table->integer('experience_years')->nullable()->after('hire_date');
            $table->text('qualifications')->nullable()->after('experience_years');
            $table->text('certifications')->nullable()->after('qualifications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            // Add back old columns
            $table->string('name');
            $table->string('role_position');
            $table->string('certification_level')->nullable();
            $table->string('certification_number')->nullable();
            $table->date('certification_expiry')->nullable();
            $table->string('contact_information')->nullable();
            $table->text('specialties')->nullable();
            
            // Drop new columns
            $table->dropColumn([
                'first_name', 'last_name', 'position', 'department', 'employee_id',
                'supervisor', 'hire_date', 'experience_years', 'qualifications', 'certifications'
            ]);
        });
    }
};
