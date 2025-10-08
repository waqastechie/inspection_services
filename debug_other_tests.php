<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inspection;

try {
    echo "=== DEBUG OTHER TESTS SELECTION ===\n\n";
    
    // Load inspection 7
    $inspection = Inspection::with(['otherTest', 'loadTest', 'mpiInspection', 'liftingExamination'])->find(7);
    
    if (!$inspection) {
        echo "Inspection 7 not found!\n";
        exit;
    }
    
    echo "Inspection ID: " . $inspection->id . "\n";
    echo "Equipment: " . $inspection->equipment_id . "\n\n";
    
    // Check services accessor
    echo "=== SERVICES ACCESSOR ===\n";
    $services = $inspection->services;
    echo "Services count: " . $services->count() . "\n";
    
    foreach ($services as $service) {
        echo "- Service Type: " . $service->service_type . "\n";
        echo "  Service Name: " . (isset($service->service_name) ? $service->service_name : 'N/A') . "\n";
        echo "  Has Data: " . (isset($service->has_data) && $service->has_data ? 'Yes' : 'No') . "\n";
        echo "  Service Object: " . json_encode($service) . "\n\n";
    }
    
    // Check individual relationships
    echo "=== INDIVIDUAL RELATIONSHIPS ===\n";
    echo "Other Test: " . ($inspection->otherTest ? 'EXISTS' : 'NULL') . "\n";
    echo "Load Test: " . ($inspection->loadTest ? 'EXISTS' : 'NULL') . "\n";
    echo "MPI Inspection: " . ($inspection->mpiInspection ? 'EXISTS' : 'NULL') . "\n";
    echo "Lifting Examination: " . ($inspection->liftingExamination ? 'EXISTS' : 'NULL') . "\n\n";
    
    // Check Other Test details if it exists
    if ($inspection->otherTest) {
        echo "=== OTHER TEST DETAILS ===\n";
        $otherTest = $inspection->otherTest;
        echo "ID: " . $otherTest->id . "\n";
        echo "Inspection ID: " . $otherTest->inspection_id . "\n";
        echo "Drop Test: " . ($otherTest->drop_test ? 'Yes' : 'No') . "\n";
        echo "Tilt Test: " . ($otherTest->tilt_test ? 'Yes' : 'No') . "\n";
        echo "Lowering Test: " . ($otherTest->lowering_test ? 'Yes' : 'No') . "\n";
        echo "Created At: " . $otherTest->created_at . "\n";
        echo "Updated At: " . $otherTest->updated_at . "\n";
    }
    
    // Check what service types should be pre-selected
    echo "=== SERVICE TYPES FOR PRE-SELECTION ===\n";
    $serviceTypes = $inspection->services->pluck('service_type')->toArray();
    echo "Service types array: " . json_encode($serviceTypes) . "\n";
    echo "Contains 'other-services': " . (in_array('other-services', $serviceTypes) ? 'YES' : 'NO') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}