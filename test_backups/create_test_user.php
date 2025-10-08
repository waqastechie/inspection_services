<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Check if user already exists
    $existingUser = App\Models\User::where('email', 'test@example.com')->first();
    
    if ($existingUser) {
        echo "Test user already exists!\n";
        echo "Email: test@example.com\n";
        echo "Password: password\n";
        exit;
    }

    // Create test user
    $user = App\Models\User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'role' => 'super_admin', // Give full access for testing
        'status' => 'active'
    ]);

    echo "Test user created successfully!\n";
    echo "Email: test@example.com\n";
    echo "Password: password\n";
    echo "Role: super_admin\n";

} catch (Exception $e) {
    echo "Error creating test user: " . $e->getMessage() . "\n";
}
