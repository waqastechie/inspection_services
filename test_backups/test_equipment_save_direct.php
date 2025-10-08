<?php
require_once __DIR__.'/bootstrap/app.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Inspection;
use App\Http\Controllers\InspectionController;

echo "Testing Equipment Saving for Inspection ID 5\n";
echo "==========================================\n\n";

// Find inspection 5
$inspection = Inspection::find(5);
if (!$inspection) {
    echo "ERROR: Inspection 5 not found!\n";
    exit;
}

echo "✓ Found inspection: {$inspection->inspection_no} - {$inspection->client_name}\n";

// Check current equipment count
$currentCount = $inspection->equipment()->count();
echo "Current equipment count: {$currentCount}\n\n";

// Test data
$testEquipmentData = [
    [
        'equipment_type' => 'Test Equipment 1',
        'serial_number' => 'TEST001',
        'description' => 'Test equipment for debugging',
        'reason_for_examination' => 'Initial examination',
        'model' => 'TEST-MODEL-1',
        'status' => 'pass',
        'category' => 'item'
    ],
    [
        'equipment_type' => 'Test Equipment 2', 
        'serial_number' => 'TEST002',
        'description' => 'Another test equipment',
        'reason_for_examination' => 'Periodic examination',
        'model' => 'TEST-MODEL-2',
        'status' => 'pass',
        'category' => 'item'
    ]
];

echo "Test equipment data:\n";
echo json_encode($testEquipmentData, JSON_PRETTY_PRINT) . "\n\n";

// Test the saveEquipmentData method using reflection
try {
    $controller = new InspectionController();
    
    // Use reflection to call private method
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('saveEquipmentData');
    $method->setAccessible(true);
    
    echo "Calling saveEquipmentData method...\n";
    $method->invoke($controller, $inspection, json_encode($testEquipmentData));
    
    // Check new count
    $newCount = $inspection->fresh()->equipment()->count();
    echo "Equipment count after save: {$newCount}\n";
    
    if ($newCount > $currentCount) {
        echo "✓ SUCCESS: Equipment was saved! Added " . ($newCount - $currentCount) . " records\n";
        
        // Show the saved records
        echo "\nSaved equipment records:\n";
        $equipment = $inspection->fresh()->equipment()->latest()->take($newCount - $currentCount)->get();
        foreach ($equipment as $item) {
            echo "- ID {$item->id}: {$item->equipment_type} ({$item->serial_number})\n";
        }
    } else {
        echo "✗ FAILED: No new equipment was saved\n";
    }
    
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
?>