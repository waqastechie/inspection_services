<?php

// Direct test of the personnel API endpoint
require_once 'vendor/autoload.php';

// Test the Personnel model directly
use App\Models\Personnel;

try {
    // Test if we can create personnel records
    $personnel = [
        [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'position' => 'Senior Inspector',
            'department' => 'Quality Assurance',
            'employee_id' => 'EMP001',
            'email' => 'john.smith@company.com',
            'phone' => '+1-555-0101',
            'qualifications' => 'ASNT Level III, API 510',
            'certifications' => 'NDT Level 3 (UT, RT, MT, PT)',
            'is_active' => true,
        ],
        [
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'position' => 'Lead Inspector',
            'department' => 'NDT Services',
            'employee_id' => 'EMP002',
            'email' => 'sarah.johnson@company.com',
            'phone' => '+1-555-0102',
            'qualifications' => 'ASNT Level II, LEEA Inspector',
            'certifications' => 'NDT Level 2 (UT, MT, PT)',
            'is_active' => true,
        ]
    ];
    
    // Clear existing and insert new
    Personnel::truncate();
    
    foreach ($personnel as $person) {
        Personnel::create($person);
    }
    
    echo "Successfully created personnel records!\n";
    
    // Test the API response format
    $activePersonnel = Personnel::where('is_active', true)->get();
    $formattedPersonnel = $activePersonnel->map(function($person) {
        return [
            'id' => $person->id,
            'name' => $person->first_name . ' ' . $person->last_name,
            'position' => $person->position,
            'department' => $person->department,
            'qualifications' => $person->qualifications,
            'certifications' => $person->certifications,
            'contact' => $person->phone . ($person->email ? ' / ' . $person->email : ''),
        ];
    });
    
    echo "API Response would be:\n";
    echo json_encode($formattedPersonnel, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
