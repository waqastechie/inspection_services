<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

echo "=== CHECKING LATEST INSPECTION DATA ===\n\n";

try {
    // Get the latest inspection
    $inspection = App\Models\Inspection::latest()->first();
    
    if (!$inspection) {
        echo "❌ No inspections found in database\n";
        exit;
    }
    
    echo "📋 Inspection ID: {$inspection->id}\n";
    echo "📋 Inspection Number: {$inspection->inspection_number}\n";
    echo "📅 Created: {$inspection->created_at}\n\n";
    
    // Check basic fields
    echo "=== BASIC INFORMATION ===\n";
    echo "Client: " . ($inspection->client_name ?? 'NULL') . "\n";
    echo "Project: " . ($inspection->project_name ?? 'NULL') . "\n";
    echo "Location: " . ($inspection->location ?? 'NULL') . "\n";
    echo "Inspection Date: " . ($inspection->inspection_date ?? 'NULL') . "\n";
    echo "Lead Inspector: " . ($inspection->lead_inspector_name ?? 'NULL') . "\n";
    echo "Certification: " . ($inspection->lead_inspector_certification ?? 'NULL') . "\n";
    echo "Weather: " . ($inspection->weather_conditions ?? 'NULL') . "\n\n";
    
    // Check additional fields that might not be saving
    echo "=== ADDITIONAL FIELDS ===\n";
    echo "Area of Examination: " . ($inspection->area_of_examination ?? 'NULL') . "\n";
    echo "Services Performed: " . ($inspection->services_performed ?? 'NULL') . "\n";
    echo "Contract: " . ($inspection->contract ?? 'NULL') . "\n";
    echo "Work Order: " . ($inspection->work_order ?? 'NULL') . "\n";
    echo "Purchase Order: " . ($inspection->purchase_order ?? 'NULL') . "\n";
    echo "Job Reference: " . ($inspection->job_ref ?? 'NULL') . "\n";
    echo "Standards: " . ($inspection->standards ?? 'NULL') . "\n";
    echo "Inspector Comments: " . ($inspection->inspector_comments ?? 'NULL') . "\n\n";
    
    // Check equipment information
    echo "=== EQUIPMENT INFORMATION ===\n";
    echo "Equipment Type: " . ($inspection->equipment_type ?? 'NULL') . "\n";
    echo "Equipment Description: " . ($inspection->equipment_description ?? 'NULL') . "\n";
    echo "Manufacturer: " . ($inspection->manufacturer ?? 'NULL') . "\n";
    echo "Model: " . ($inspection->model ?? 'NULL') . "\n";
    echo "Serial Number: " . ($inspection->serial_number ?? 'NULL') . "\n";
    echo "Capacity: " . ($inspection->capacity ?? 'NULL') . "\n\n";
    
    // Check service inspectors
    echo "=== SERVICE INSPECTORS ===\n";
    echo "Lifting Examination Inspector: " . ($inspection->lifting_examination_inspector ?? 'NULL') . "\n";
    echo "Load Test Inspector: " . ($inspection->load_test_inspector ?? 'NULL') . "\n";
    echo "Thorough Examination Inspector: " . ($inspection->thorough_examination_inspector ?? 'NULL') . "\n";
    echo "MPI Service Inspector: " . ($inspection->mpi_service_inspector ?? 'NULL') . "\n";
    echo "Visual Inspector: " . ($inspection->visual_inspector ?? 'NULL') . "\n\n";
    
    // Summary
    $totalFields = 0;
    $filledFields = 0;
    
    $fieldsToCheck = [
        'area_of_examination', 'services_performed', 'contract', 'work_order',
        'purchase_order', 'job_ref', 'standards', 'inspector_comments',
        'equipment_description', 'manufacturer', 'model', 'serial_number',
        'lifting_examination_inspector', 'load_test_inspector'
    ];
    
    foreach ($fieldsToCheck as $field) {
        $totalFields++;
        if (!empty($inspection->$field)) {
            $filledFields++;
        }
    }
    
    echo "📊 SUMMARY:\n";
    echo "Fields filled: $filledFields/$totalFields\n";
    
    if ($filledFields < $totalFields / 2) {
        echo "❌ PROBLEM: Most fields are empty - likely a form submission issue\n";
        echo "💡 Possible causes:\n";
        echo "   - Form not sending all data\n";
        echo "   - JavaScript issues\n";
        echo "   - Validation blocking fields\n";
        echo "   - Controller not processing all fields\n";
    } else {
        echo "✅ Data seems to be saving properly\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
