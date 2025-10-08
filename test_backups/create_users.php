<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== MANUAL USER CREATION ===\n";

try {
    // Check current user count
    $userCount = User::count();
    echo "Current user count: $userCount\n";

    if ($userCount == 0) {
        echo "Creating admin users...\n";
        
        // Create Super Admin user
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@inspectionservices.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'is_active' => true,
            'department' => 'Administration',
            'certification' => 'System Administrator',
        ]);
        echo "✅ Super Admin created (ID: {$superAdmin->id})\n";

        // Create sample admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@company.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'department' => 'Management',
        ]);
        echo "✅ Admin User created (ID: {$admin->id})\n";

        // Create sample inspector
        $inspector = User::create([
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
        echo "✅ Inspector created (ID: {$inspector->id})\n";
        
        echo "\nTotal users created: " . User::count() . "\n";
        
        echo "\nLogin credentials:\n";
        echo "Super Admin: admin@inspectionservices.com / admin123\n";
        echo "Admin: admin@company.com / password\n";
        echo "Inspector: inspector@company.com / password\n";
        
    } else {
        echo "Users already exist. Current users:\n";
        User::all()->each(function($user) {
            echo "- {$user->name} ({$user->email}) - Role: {$user->role}\n";
        });
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== COMPLETE ===\n";
