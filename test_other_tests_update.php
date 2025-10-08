<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inspection;
use App\Models\OtherTest;

try {
    echo "=== UPDATING OTHER TEST DATA FOR TESTING ===\n\n";
    
    // Load inspection 7
    $inspection = Inspection::find(7);
    
    if (!$inspection) {
        echo "Inspection 7 not found!\n";
        exit;
    }
    
    // Get or create the Other Test record
    $otherTest = $inspection->otherTest;
    
    if (!$otherTest) {
        echo "Creating new Other Test record for inspection 7...\n";
        $otherTest = new OtherTest();
        $otherTest->inspection_id = $inspection->id;
    }
    
    echo "Current Other Test data:\n";
    echo "- Drop Test Load: " . ($otherTest->drop_test_load ?? 'NULL') . "\n";
    echo "- Tilt Test Load: " . ($otherTest->tilt_test_load ?? 'NULL') . "\n";
    echo "- Lowering Test Load: " . ($otherTest->lowering_test_load ?? 'NULL') . "\n\n";
    
    // Update with test data
    // Drop Test data
    $otherTest->drop_test_load = '1000kg';
    $otherTest->drop_type = 'Free Fall';
    $otherTest->drop_distance = '2m';
    $otherTest->drop_suspended = 'Yes';
    $otherTest->drop_impact_speed = '6.26 m/s';
    $otherTest->drop_result = 'Pass';
    $otherTest->drop_notes = 'Test completed successfully';
    
    // Tilt Test data
    $otherTest->tilt_test_load = '500kg';
    $otherTest->loaded_tilt = '15 degrees';
    $otherTest->empty_tilt = '5 degrees';
    $otherTest->tilt_results = 'Pass';
    $otherTest->tilt_stability = 'Stable';
    $otherTest->tilt_direction = 'Forward';
    $otherTest->tilt_duration = '30 seconds';
    $otherTest->tilt_notes = 'Stability maintained';
    
    // Leave Lowering Test empty to test partial selection
    
    $otherTest->save();
    
    echo "Updated Other Test data:\n";
    echo "- Drop Test Load: " . ($otherTest->drop_test_load ?? 'NULL') . "\n";
    echo "- Tilt Test Load: " . ($otherTest->tilt_test_load ?? 'NULL') . "\n";
    echo "- Lowering Test Load: " . ($otherTest->lowering_test_load ?? 'NULL') . "\n\n";
    
    echo "Other Test record updated successfully!\n";
    echo "Now Drop Test and Tilt Test should be pre-selected when editing inspection 7.\n";
    echo "Lowering Test should NOT be pre-selected since it has no data.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}