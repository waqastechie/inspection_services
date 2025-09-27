<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding users...');

        // Clear existing users to avoid unique constraint errors
        \DB::table('users')->delete();

        // Super Admin User
        User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@inspectionservices.com',
            'password' => 'admin123',
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // Admin User
        User::create([
            'name' => 'System Admin',
            'email' => 'sysadmin@inspection.com',
            'password' => 'password',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // QA User
        User::create([
            'name' => 'Quality Assurance Manager',
            'email' => 'qa@inspection.com',
            'password' => 'password',
            'role' => 'qa',
            'email_verified_at' => now(),
        ]);

        // Client User
        User::create([
            'name' => 'Client User',
            'email' => 'client@inspection.com',
            'password' => 'password',
            'role' => 'client',
            'email_verified_at' => now(),
        ]);

        // Lead Inspector
        User::create([
            'name' => 'John Inspector',
            'email' => 'inspector@inspection.com',
            'password' => 'password',
            'role' => 'inspector',
            'email_verified_at' => now(),
        ]);

        // Senior Inspector
        User::create([
            'name' => 'Sarah Williams',
            'email' => 'sarah.williams@inspection.com',
            'password' => 'password',
            'role' => 'inspector',
            'email_verified_at' => now(),
        ]);

        // QA Inspector 
        User::create([
            'name' => 'Michael QA Lead',
            'email' => 'michael.qa@inspection.com',
            'password' => 'password',
            'role' => 'qa', // now using 'qa' role
            'email_verified_at' => now(),
        ]);

        // Junior Inspector
        User::create([
            'name' => 'David Chen',
            'email' => 'david.chen@inspection.com',
            'password' => 'password',
            'role' => 'inspector',
            'email_verified_at' => now(),
        ]);

        // Test User for Development
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'inspector',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✓ Users seeded successfully');
    }
}
