<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inspection;

echo "=== Testing Services Relationship for Inspection ID 7 ===\n\n";

try {
    $inspection = Inspection::find(7);
    
    if (!$inspection) {
        echo "Inspection ID 7 not found!\n";
        exit(1);
    }
    
    echo "Inspection found: ID {$inspection->id}\n";
    
    // Test individual relationships
    echo "\n--- Individual Relationships ---\n";
    echo "Load Test: " . ($inspection->loadTest ? "Found (ID: {$inspection->loadTest->id})" : "Not found") . "\n";
    echo "MPI Inspection: " . ($inspection->mpiInspection ? "Found (ID: {$inspection->mpiInspection->id})" : "Not found") . "\n";
    echo "Other Test: " . ($inspection->otherTest ? "Found (ID: {$inspection->otherTest->id})" : "Not found") . "\n";
    echo "Lifting Examination: " . ($inspection->liftingExamination ? "Found (ID: {$inspection->liftingExamination->id})" : "Not found") . "\n";
    
    // Test services collection
    echo "\n--- Services Collection ---\n";
    $services = $inspection->services;
    echo "Services found: " . $services->count() . "\n";
    
    foreach ($services as $service) {
        echo "- {$service->service_type} (ID: {$service->id}, Status: {$service->status})\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\n=== Test Complete ===\n";