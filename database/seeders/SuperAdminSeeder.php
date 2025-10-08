<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin user
        User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@inspectionservices.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'is_active' => true,
            'department' => 'Administration',
            'certification' => 'System Administrator',
        ]);

        // Create sample admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@company.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'department' => 'Management',
        ]);

        // Create sample inspector
        User::create([
            'name' => 'John Inspector',
            'email' => 'inspector@company.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'inspector',
            'is_active' => true,
            'department' => 'Quality Assurance',
            'certification' => 'Level II NDT Inspector',
            'certification_expiry' => now()->addYear(),
        ]);
    }
}
