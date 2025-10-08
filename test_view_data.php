<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inspection;

echo "=== Testing View Data for Inspection ID 7 ===\n\n";

try {
    $inspection = Inspection::find(7);
    
    if (!$inspection) {
        echo "Inspection ID 7 not found!\n";
        exit(1);
    }
    
    echo "Inspection found: ID {$inspection->id}\n";
    
    // Test services collection
    $services = $inspection->services;
    echo "Services count: " . $services->count() . "\n";
    
    // Test what would be passed to the view
    echo "\nServices data that would be passed to view:\n";
    echo json_encode($services->toArray(), JSON_PRETTY_PRINT) . "\n";
    
    // Test service types pluck
    echo "\nService types (pluck):\n";
    $serviceTypes = $services->pluck('service_type');
    echo json_encode($serviceTypes->toArray(), JSON_PRETTY_PRINT) . "\n";
    
    // Test the condition used in the blade template
    echo "\nBlade template condition check:\n";
    echo "services->count() > 0: " . ($services->count() > 0 ? 'true' : 'false') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\n=== Test Complete ===\n";