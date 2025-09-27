<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->boot();

use App\Models\User;
use App\Models\Client;
use App\Models\Personnel;
use App\Models\Equipment;
use App\Models\Consumable;
use Illuminate\Support\Facades\Hash;

echo "Starting database seeding...\n";

// Create Users
echo "Creating users...\n";
$users = [
    ['name' => 'Admin User', 'email' => 'admin@company.com', 'password' => Hash::make('password'), 'role' => 'admin', 'is_active' => true],
    ['name' => 'Inspector User', 'email' => 'inspector@company.com', 'password' => Hash::make('password'), 'role' => 'inspector', 'is_active' => true],
    ['name' => 'QA Manager', 'email' => 'qa@company.com', 'password' => Hash::make('password'), 'role' => 'qa', 'is_active' => true],
];

foreach ($users as $userData) {
    $existingUser = User::where('email', $userData['email'])->first();
    if (!$existingUser) {
        User::create($userData);
        echo "Created user: {$userData['email']}\n";
    } else {
        echo "User exists: {$userData['email']}\n";
    }
}

// Create Clients
echo "Creating clients...\n";
$clients = [
    ['client_name' => 'Acme Corporation', 'client_code' => 'ACME001', 'contact_person' => 'John Smith', 'contact_email' => 'john@acme.com', 'phone' => '+1-555-0101', 'is_active' => true],
    ['client_name' => 'Global Industries', 'client_code' => 'GLOB001', 'contact_person' => 'Jane Doe', 'contact_email' => 'jane@global.com', 'phone' => '+1-555-0102', 'is_active' => true],
    ['client_name' => 'Tech Solutions', 'client_code' => 'TECH001', 'contact_person' => 'Bob Johnson', 'contact_email' => 'bob@tech.com', 'phone' => '+1-555-0103', 'is_active' => true],
];

foreach ($clients as $clientData) {
    $existingClient = Client::where('client_code', $clientData['client_code'])->first();
    if (!$existingClient) {
        Client::create($clientData);
        echo "Created client: {$clientData['client_name']}\n";
    } else {
        echo "Client exists: {$clientData['client_name']}\n";
    }
}

// Create Personnel
echo "Creating personnel...\n";
$personnel = [
    ['first_name' => 'John', 'last_name' => 'Smith', 'position' => 'Senior Inspector', 'department' => 'Quality Assurance', 'employee_id' => 'EMP001', 'email' => 'john.smith@company.com', 'phone' => '+1-555-0101', 'is_active' => true],
    ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'position' => 'Lead Inspector', 'department' => 'NDT Services', 'employee_id' => 'EMP002', 'email' => 'sarah.johnson@company.com', 'phone' => '+1-555-0102', 'is_active' => true],
    ['first_name' => 'Michael', 'last_name' => 'Chen', 'position' => 'Quality Technician', 'department' => 'Quality Control', 'employee_id' => 'EMP003', 'email' => 'michael.chen@company.com', 'phone' => '+1-555-0103', 'is_active' => true],
];

foreach ($personnel as $personData) {
    $existingPerson = Personnel::where('employee_id', $personData['employee_id'])->first();
    if (!$existingPerson) {
        Personnel::create($personData);
        echo "Created personnel: {$personData['first_name']} {$personData['last_name']}\n";
    } else {
        echo "Personnel exists: {$personData['first_name']} {$personData['last_name']}\n";
    }
}

// Create Equipment
echo "Creating equipment...\n";
$equipment = [
    ['name' => 'Ultrasonic Testing Device', 'type' => 'NDT Equipment', 'serial_number' => 'UT001', 'brand_model' => 'UT-Pro-2000', 'equipment_category' => 'asset', 'is_active' => true],
    ['name' => 'Magnetic Particle Tester', 'type' => 'NDT Equipment', 'serial_number' => 'MT001', 'brand_model' => 'MT-Master-500', 'equipment_category' => 'asset', 'is_active' => true],
    ['name' => 'Digital Caliper', 'type' => 'Measuring Tool', 'serial_number' => 'CAL001', 'brand_model' => 'DC-Precision-150', 'equipment_category' => 'asset', 'is_active' => true],
];

foreach ($equipment as $equipData) {
    $existingEquip = Equipment::where('serial_number', $equipData['serial_number'])->first();
    if (!$existingEquip) {
        Equipment::create($equipData);
        echo "Created equipment: {$equipData['name']}\n";
    } else {
        echo "Equipment exists: {$equipData['name']}\n";
    }
}

// Create Consumables
echo "Creating consumables...\n";
$consumables = [
    ['name' => 'Ultrasonic Gel', 'type' => 'Coupling Agent', 'unit' => 'bottle', 'quantity_available' => 50, 'is_active' => true],
    ['name' => 'Magnetic Particles', 'type' => 'Test Medium', 'unit' => 'kg', 'quantity_available' => 25, 'is_active' => true],
    ['name' => 'Penetrant Spray', 'type' => 'Test Chemical', 'unit' => 'can', 'quantity_available' => 30, 'is_active' => true],
];

foreach ($consumables as $consumableData) {
    $existingConsumable = Consumable::where('name', $consumableData['name'])->first();
    if (!$existingConsumable) {
        Consumable::create($consumableData);
        echo "Created consumable: {$consumableData['name']}\n";
    } else {
        echo "Consumable exists: {$consumableData['name']}\n";
    }
}

echo "\nSeeding completed!\n";
echo "Final counts:\n";
echo "Users: " . User::count() . "\n";
echo "Clients: " . Client::count() . "\n";
echo "Personnel: " . Personnel::count() . "\n";
echo "Equipment: " . Equipment::count() . "\n";
echo "Consumables: " . Consumable::count() . "\n";
