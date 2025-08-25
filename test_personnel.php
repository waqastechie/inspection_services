<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Database configuration
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database/database.sqlite',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Test personnel query
try {
    $personnel = DB::table('personnels')->get();
    echo "Personnel count: " . count($personnel) . "\n";
    
    if (count($personnel) == 0) {
        // Insert sample personnel
        DB::table('personnels')->insert([
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'position' => 'Senior Inspector',
                'department' => 'Quality Assurance',
                'employee_id' => 'EMP001',
                'email' => 'john.smith@company.com',
                'phone' => '+1-555-0101',
                'supervisor' => 'Alice Johnson',
                'hire_date' => '2020-01-15',
                'experience_years' => 12,
                'qualifications' => 'ASNT Level III, API 510, ACCP Level 2',
                'certifications' => 'NDT Level 3 (UT, RT, MT, PT), API 570, NACE Level 2',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'position' => 'Lead Inspector',
                'department' => 'NDT Services',
                'employee_id' => 'EMP002',
                'email' => 'sarah.johnson@company.com',
                'phone' => '+1-555-0102',
                'supervisor' => 'Michael Brown',
                'hire_date' => '2019-03-20',
                'experience_years' => 8,
                'qualifications' => 'ASNT Level II, LEEA Inspector',
                'certifications' => 'NDT Level 2 (UT, MT, PT), LEEA Lifting Inspector',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'position' => 'Quality Technician',
                'department' => 'Quality Control',
                'employee_id' => 'EMP003',
                'email' => 'michael.chen@company.com',
                'phone' => '+1-555-0103',
                'supervisor' => 'John Smith',
                'hire_date' => '2021-08-10',
                'experience_years' => 5,
                'qualifications' => 'ASNT Level I, AWS Certified',
                'certifications' => 'NDT Level 1 (MT, PT), AWS CWI',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        echo "Inserted 3 sample personnel records\n";
    }
    
    // List all personnel
    $personnel = DB::table('personnels')->get();
    foreach ($personnel as $person) {
        echo "- {$person->first_name} {$person->last_name} ({$person->position})\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

function now() {
    return date('Y-m-d H:i:s');
}
