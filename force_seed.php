<?php

// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\User;
use App\Models\Client;
use App\Models\Inspection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== FORCE SEEDING DATABASE ===\n";

try {
    // Start transaction
    DB::beginTransaction();
    
    echo "Creating users...\n";
    
    // Clear existing users first (if any)
    User::truncate();
    
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
    echo "✅ Super Admin created: {$superAdmin->name} ({$superAdmin->email})\n";

    // Create Admin user
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@company.com',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'role' => 'admin',
        'is_active' => true,
        'department' => 'Management',
    ]);
    echo "✅ Admin created: {$admin->name} ({$admin->email})\n";

    // Create Inspector
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
    echo "✅ Inspector created: {$inspector->name} ({$inspector->email})\n";

    echo "\nCreating clients...\n";
    
    // Clear existing clients first (if any)
    Client::truncate();
    
    // Create sample clients
    $clients = [
        [
            'name' => 'ABC Manufacturing Ltd',
            'email' => 'contact@abcmanufacturing.com',
            'phone' => '+1-555-0101',
            'address' => '123 Industrial Way, Manufacturing District',
            'contact_person' => 'Sarah Johnson',
            'contact_phone' => '+1-555-0102',
        ],
        [
            'name' => 'XYZ Engineering Corp',
            'email' => 'info@xyzengineering.com',
            'phone' => '+1-555-0201',
            'address' => '456 Engineering Blvd, Tech Park',
            'contact_person' => 'Mike Wilson',
            'contact_phone' => '+1-555-0202',
        ],
        [
            'name' => 'Global Industries Inc',
            'email' => 'contact@globalindustries.com',
            'phone' => '+1-555-0301',
            'address' => '789 Corporate Drive, Business Center',
            'contact_person' => 'Lisa Davis',
            'contact_phone' => '+1-555-0302',
        ],
    ];

    foreach ($clients as $clientData) {
        $client = Client::create($clientData);
        echo "✅ Client created: {$client->name}\n";
    }

    echo "\nCreating sample inspections...\n";
    
    // Clear existing inspections first (if any)
    Inspection::truncate();
    
    // Create sample inspections
    $inspections = [
        [
            'inspection_number' => 'INS-2025-001',
            'client_id' => 1,
            'status' => 'completed',
            'equipment_location' => 'Building A - Floor 2',
            'equipment_description' => 'Overhead Crane System',
            'overall_result' => 'passed',
        ],
        [
            'inspection_number' => 'INS-2025-002',
            'client_id' => 2,
            'status' => 'in_progress',
            'equipment_location' => 'Warehouse Section B',
            'equipment_description' => 'Material Handling Equipment',
            'overall_result' => 'pending',
        ],
        [
            'inspection_number' => 'INS-2025-003',
            'client_id' => 3,
            'status' => 'draft',
            'equipment_location' => 'Production Line 1',
            'equipment_description' => 'Lifting Chains and Hooks',
            'overall_result' => 'pending',
        ],
    ];

    foreach ($inspections as $inspectionData) {
        $inspection = Inspection::create($inspectionData);
        echo "✅ Inspection created: {$inspection->inspection_number}\n";
    }
    
    // Commit transaction
    DB::commit();
    
    echo "\n=== SEEDING COMPLETE ===\n";
    echo "Users created: " . User::count() . "\n";
    echo "Clients created: " . Client::count() . "\n";
    echo "Inspections created: " . Inspection::count() . "\n";
    
    echo "\n🔑 LOGIN CREDENTIALS:\n";
    echo "Super Admin: admin@inspectionservices.com / admin123\n";
    echo "Admin: admin@company.com / password\n";
    echo "Inspector: inspector@company.com / password\n";
    
} catch (Exception $e) {
    DB::rollback();
    echo "❌ Error during seeding: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== DONE ===\n";
