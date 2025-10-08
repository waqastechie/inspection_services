<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inspection;

echo "=== Testing Pluck Method for Services ===\n\n";

try {
    $inspection = Inspection::find(7);
    $services = $inspection->services;
    
    echo "All services:\n";
    foreach ($services as $service) {
        echo "- {$service->service_type}\n";
    }
    
    echo "\nPluck result:\n";
    $plucked = $services->pluck('service_type');
    print_r($plucked->toArray());
    
    echo "\nManual array creation:\n";
    $manual = [];
    foreach ($services as $service) {
        $manual[] = $service->service_type;
    }
    print_r($manual);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";