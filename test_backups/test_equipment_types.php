<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\EquipmentType;

// Simple test to check if equipment types are working
try {
    // Initialize Laravel app
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "Testing EquipmentType model...\n";
    
    $types = EquipmentType::all();
    echo "Found " . $types->count() . " equipment types:\n";
    
    foreach ($types as $type) {
        echo "- {$type->name} ({$type->code}) - Category: {$type->category}\n";
        echo "  Requires calibration: " . ($type->requires_calibration ? 'Yes' : 'No') . "\n";
        echo "  Default services: " . implode(', ', $type->default_services ?? []) . "\n\n";
    }
    
    echo "Equipment types working correctly!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}