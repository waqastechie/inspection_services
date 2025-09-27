<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Http\Controllers\Api\EquipmentTypeController;
use App\Http\Controllers\Api\EquipmentController;
use Illuminate\Http\Request;

// Simple test to check if our API controllers work
try {
    // Initialize Laravel app
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "Testing API Controllers...\n\n";
    
    // Test EquipmentTypeController
    echo "=== Testing EquipmentTypeController ===\n";
    $equipmentTypeController = new EquipmentTypeController();
    $request = new Request();
    
    $response = $equipmentTypeController->index($request);
    $data = json_decode($response->getContent(), true);
    
    if ($data['success']) {
        echo "Equipment Types API working! Found " . count($data['data']) . " equipment types.\n";
        foreach ($data['data'] as $type) {
            echo "- {$type['name']} ({$type['category']})\n";
        }
    } else {
        echo "Equipment Types API failed: " . $data['error'] . "\n";
    }
    
    echo "\n=== Testing EquipmentController ===\n";
    $equipmentController = new EquipmentController();
    
    $response = $equipmentController->index($request);
    $data = json_decode($response->getContent(), true);
    
    if ($data['success']) {
        echo "Equipment API working! Found " . count($data['data']) . " equipment items.\n";
        foreach ($data['data'] as $equipment) {
            echo "- {$equipment['name']} ({$equipment['serial_number']})\n";
        }
    } else {
        echo "Equipment API failed: " . $data['error'] . "\n";
    }
    
    echo "\n=== Testing Categories API ===\n";
    $response = $equipmentTypeController->categories();
    $data = json_decode($response->getContent(), true);
    
    if ($data['success']) {
        echo "Categories API working! Found " . count($data['data']) . " categories.\n";
        foreach ($data['data'] as $category) {
            echo "- {$category['category_display']}: {$category['count']} types\n";
        }
    } else {
        echo "Categories API failed: " . $data['error'] . "\n";
    }
    
    echo "\nAPI Controllers working correctly!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}