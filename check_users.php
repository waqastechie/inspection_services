<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Check database connection
    echo "Testing database connection...\n";
    $pdo = DB::connection()->getPdo();
    echo "Database connected successfully!\n\n";

    // Check if users table exists and has data
    $users = App\Models\User::all();
    echo "Users in database: " . $users->count() . "\n";
    
    if ($users->count() > 0) {
        echo "Existing users:\n";
        foreach ($users as $user) {
            echo "- " . $user->name . " (" . $user->email . ") - Role: " . $user->role . "\n";
        }
    } else {
        echo "No users found. Creating test user...\n";
        
        $user = App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'status' => 'active'
        ]);
        
        echo "Test user created!\n";
        echo "Email: test@example.com\n";
        echo "Password: password\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
