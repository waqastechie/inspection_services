<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

try {
    // Check if inspection exists
    $inspection = App\Models\Inspection::first();
    
    if (!$inspection) {
        echo "Creating test inspection...\n";
        
        // Create test inspection
        $inspection = App\Models\Inspection::create([
            'inspection_number' => 'TEST-001',
            'client_name' => 'Test Client Inc.',
            'project_name' => 'Test Project',
            'location' => 'Test Location',
            'inspection_date' => now(),
            'lead_inspector_name' => 'John Smith',
            'lead_inspector_certification' => 'ASNT Level III, API 510',
            'equipment_type' => 'Lifting Equipment',
            'equipment_description' => 'Test Equipment',
            'applicable_standard' => 'DNV 2.7-1',
            'weather_conditions' => 'Clear',
            'status' => 'draft',
        ]);
        
        echo "Test inspection created with ID: " . $inspection->id . "\n";
    } else {
        echo "Inspection already exists with ID: " . $inspection->id . "\n";
    }
    
    echo "Lead Inspector: " . $inspection->lead_inspector_name . "\n";
    echo "Edit URL: http://127.0.0.1:8001/inspections/" . $inspection->id . "/edit\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
