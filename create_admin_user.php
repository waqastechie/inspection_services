<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "🔍 Checking for existing admin user...\n";
    
    // Check if admin user exists
    $adminUser = User::where('email', 'admin@inspectionservices.com')->first();
    
    if ($adminUser) {
        echo "✅ Admin user found: {$adminUser->name} ({$adminUser->email})\n";
        echo "🔄 Updating password to 'admin123'...\n";
        
        $adminUser->update([
            'password' => Hash::make('admin123'),
            'is_active' => true,
        ]);
        
        echo "✅ Admin user password updated successfully!\n";
    } else {
        echo "❌ Admin user not found. Creating new admin user...\n";
        
        $adminUser = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@inspectionservices.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'is_active' => true,
            'department' => 'Administration',
            'certification' => 'System Administrator',
        ]);
        
        echo "✅ Admin user created successfully!\n";
    }
    
    echo "\n📋 LOGIN CREDENTIALS:\n";
    echo "Email: admin@inspectionservices.com\n";
    echo "Password: admin123\n";
    echo "Role: " . $adminUser->role . "\n";
    echo "\n🎉 You can now login to the application!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "💡 Make sure your database is properly configured and accessible.\n";
}
