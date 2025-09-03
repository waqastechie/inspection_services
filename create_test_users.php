<?php

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Creating test users for inspector functionality...\n";

// Test users data
$testUsers = [
    [
        'name' => 'John Smith',
        'email' => 'john.smith@example.com',
        'password' => Hash::make('password123'),
        'role' => 'inspector',
        'certification' => "• NDT Level II Certified\n• ASNT MT/PT Level III\n• Lifting Equipment Inspector\n• Valid until: 2026-12-31",
        'department' => 'Non-Destructive Testing',
        'phone' => '+1-555-0101',
        'is_active' => true
    ],
    [
        'name' => 'Sarah Johnson',
        'email' => 'sarah.johnson@example.com',
        'password' => Hash::make('password123'),
        'role' => 'inspector',
        'certification' => "• Certified Welding Inspector (CWI)\n• CSWIP Welding Inspector\n• API 570 Piping Inspector\n• 15+ years experience",
        'department' => 'Welding & Fabrication',
        'phone' => '+1-555-0102',
        'is_active' => true
    ],
    [
        'name' => 'Michael Brown',
        'email' => 'michael.brown@example.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'certification' => "• Senior Inspector\n• Management Certification\n• Technical Review Authority\n• Lead Inspector Qualified",
        'department' => 'Quality Assurance',
        'phone' => '+1-555-0103',
        'is_active' => true
    ],
    [
        'name' => 'Emma Wilson',
        'email' => 'emma.wilson@example.com',
        'password' => Hash::make('password123'),
        'role' => 'inspector',
        'certification' => "• Pressure Vessel Inspector\n• ASME Section VIII Certified\n• Boiler Inspector Level II\n• Valid until: 2025-10-15",
        'department' => 'Pressure Systems',
        'phone' => '+1-555-0104',
        'is_active' => true
    ],
    [
        'name' => 'David Taylor',
        'email' => 'david.taylor@example.com',
        'password' => Hash::make('password123'),
        'role' => 'super_admin',
        'certification' => "• Master Inspector\n• All NDT Methods Certified\n• Training Authority\n• ISO 9712 Level III\n• 20+ years experience",
        'department' => 'Management',
        'phone' => '+1-555-0105',
        'is_active' => true
    ]
];

// Create or update users
foreach ($testUsers as $userData) {
    $user = User::where('email', $userData['email'])->first();
    
    if ($user) {
        $user->update($userData);
        echo "Updated user: {$userData['name']} ({$userData['role']})\n";
    } else {
        User::create($userData);
        echo "Created user: {$userData['name']} ({$userData['role']})\n";
    }
}

echo "\nTest users created successfully!\n";
echo "You can now test the inspector selection functionality.\n";
echo "\nCredentials for testing:\n";
foreach ($testUsers as $user) {
    echo "- {$user['name']}: {$user['email']} / password123 ({$user['role']})\n";
}
echo "\n";
