<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@company.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true
            ],
            [
                'name' => 'Inspector User',
                'email' => 'inspector@company.com',
                'password' => Hash::make('password'),
                'role' => 'inspector',
                'is_active' => true
            ],
            [
                'name' => 'QA Manager',
                'email' => 'qa@company.com',
                'password' => Hash::make('password'),
                'role' => 'qa',
                'is_active' => true
            ],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'is_active' => true
            ]
        ];
        
        foreach ($users as $userData) {
            $existingUser = User::where('email', $userData['email'])->first();
            
            if (!$existingUser) {
                User::create($userData);
                echo "Created user: {$userData['email']}\n";
            } else {
                echo "User already exists: {$userData['email']}\n";
            }
        }
        
        echo "\nLogin credentials:\n";
        echo "Admin: admin@company.com / password\n";
        echo "Inspector: inspector@company.com / password\n";
        echo "QA Manager: qa@company.com / password\n";
        echo "Test: test@example.com / password\n";
    }
}
