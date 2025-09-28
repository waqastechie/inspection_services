<?php
/**
 * Test script to verify equipment assignments are not deleted between wizard steps
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a test inspection
echo "Creating test inspection...\n";
$inspection = \App\Models\Inspection::create([
    'client_id' => 1,
    'site_id' => 1,
    'inspector_id' => 1,
    'equipment_id' => 1,
    'inspection_date' => now()->format('Y-m-d'),
    'status' => 'draft'
]);

echo "Created inspection ID: {$inspection->id}\n";

// Simulate step 3 - Equipment Assignments (multiple equipment)
echo "\n=== STEP 3: Equipment Assignments ===\n";
$equipmentData = json_encode([
    [
        'equipment_name' => 'Test Scale 1',
        'equipment_type' => 'Scale',
        'serial_number' => 'TST001'
    ],
    [
        'equipment_name' => 'Test Scale 2', 
        'equipment_type' => 'Scale',
        'serial_number' => 'TST002'
    ]
]);

// Create request simulation for step 3
$request = new \Illuminate\Http\Request();
$request->merge([
    'equipment_data' => $equipmentData
]);

// Call the controller method for step 3
$controller = new \App\Http\Controllers\InspectionController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('saveEquipmentAssignments');
$method->setAccessible(true);
$method->invoke($controller, $inspection, $equipmentData);

// Check how many equipment assignments were created
$assignments = \App\Models\EquipmentAssignment::where('inspection_id', $inspection->id)->get();
echo "Equipment assignments after step 3: " . $assignments->count() . "\n";
foreach ($assignments as $assignment) {
    echo "- {$assignment->equipment_name} (ID: {$assignment->id})\n";
}

// Simulate step 4 - Equipment Details (single equipment details)
echo "\n=== STEP 4: Equipment Details ===\n";
$request4 = new \Illuminate\Http\Request();
$request4->merge([
    'equipment_type' => 'Test Calibrator',
    'equipment_serial' => 'CAL001',
    'equipment_manufacturer' => 'Test Mfg',
    'equipment_model' => 'Model X',
    'equipment_status' => 'good',
    'equipment_comments' => 'Additional equipment for testing'
]);

// Call the controller method for step 4
$method4 = $reflection->getMethod('saveEquipmentDetails');
$method4->setAccessible(true);
$method4->invoke($controller, $inspection, $request4);

// Check equipment assignments after step 4
$assignmentsAfter = \App\Models\EquipmentAssignment::where('inspection_id', $inspection->id)->get();
echo "Equipment assignments after step 4: " . $assignmentsAfter->count() . "\n";
foreach ($assignmentsAfter as $assignment) {
    echo "- {$assignment->equipment_name} (ID: {$assignment->id})\n";
}

// Verify the data persistence
if ($assignmentsAfter->count() >= $assignments->count()) {
    echo "\n✅ SUCCESS: Equipment assignments from step 3 were preserved!\n";
    echo "Original count: {$assignments->count()}, Final count: {$assignmentsAfter->count()}\n";
} else {
    echo "\n❌ FAILURE: Equipment assignments were deleted!\n";
    echo "Original count: {$assignments->count()}, Final count: {$assignmentsAfter->count()}\n";
}

// Cleanup
echo "\nCleaning up test data...\n";
\App\Models\EquipmentAssignment::where('inspection_id', $inspection->id)->delete();
$inspection->delete();
echo "Test completed.\n";