<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inspection;

try {
    echo "=== VERIFYING OTHER TESTS DATA FOR INSPECTION 7 ===\n\n";
    
    // Load inspection 7
    $inspection = Inspection::find(7);
    
    if (!$inspection) {
        echo "Inspection 7 not found!\n";
        exit;
    }
    
    echo "Inspection: {$inspection->inspection_number}\n";
    
    // Handle service_types as either string or array
    $serviceTypes = $inspection->service_types;
    if (is_string($serviceTypes)) {
        $serviceTypesArray = json_decode($serviceTypes, true) ?? [];
        echo "Service Types: " . $serviceTypes . "\n";
    } else {
        $serviceTypesArray = $serviceTypes ?? [];
        echo "Service Types: " . implode(', ', $serviceTypesArray) . "\n";
    }
    echo "\n";
    
    // Check if other-services is in service types
    $hasOtherServices = in_array('other-services', $serviceTypesArray);
    echo "Has 'other-services' in service_types: " . ($hasOtherServices ? 'YES' : 'NO') . "\n\n";
    
    // Get Other Test data
    $otherTest = $inspection->otherTest;
    
    if (!$otherTest) {
        echo "❌ No Other Test record found!\n";
        exit;
    }
    
    echo "=== OTHER TEST DATA ===\n";
    
    // Check Drop Test data
    echo "Drop Test:\n";
    echo "  - Load: " . ($otherTest->drop_test_load ?? 'NULL') . "\n";
    echo "  - Type: " . ($otherTest->drop_type ?? 'NULL') . "\n";
    echo "  - Distance: " . ($otherTest->drop_distance ?? 'NULL') . "\n";
    echo "  - Result: " . ($otherTest->drop_result ?? 'NULL') . "\n";
    
    $hasDropData = $otherTest->drop_test_load || $otherTest->drop_type || 
                   $otherTest->drop_distance || $otherTest->drop_suspended || 
                   $otherTest->drop_impact_speed || $otherTest->drop_result || 
                   $otherTest->drop_notes;
    echo "  - Should be pre-selected: " . ($hasDropData ? 'YES' : 'NO') . "\n\n";
    
    // Check Tilt Test data
    echo "Tilt Test:\n";
    echo "  - Load: " . ($otherTest->tilt_test_load ?? 'NULL') . "\n";
    echo "  - Loaded Tilt: " . ($otherTest->loaded_tilt ?? 'NULL') . "\n";
    echo "  - Empty Tilt: " . ($otherTest->empty_tilt ?? 'NULL') . "\n";
    echo "  - Results: " . ($otherTest->tilt_results ?? 'NULL') . "\n";
    
    $hasTiltData = $otherTest->tilt_test_load || $otherTest->loaded_tilt || 
                   $otherTest->empty_tilt || $otherTest->tilt_results || 
                   $otherTest->tilt_stability || $otherTest->tilt_direction || 
                   $otherTest->tilt_duration || $otherTest->tilt_notes;
    echo "  - Should be pre-selected: " . ($hasTiltData ? 'YES' : 'NO') . "\n\n";
    
    // Check Lowering Test data
    echo "Lowering Test:\n";
    echo "  - Load: " . ($otherTest->lowering_test_load ?? 'NULL') . "\n";
    echo "  - Impact Speed: " . ($otherTest->lowering_impact_speed ?? 'NULL') . "\n";
    echo "  - Result: " . ($otherTest->lowering_result ?? 'NULL') . "\n";
    echo "  - Method: " . ($otherTest->lowering_method ?? 'NULL') . "\n";
    
    $hasLoweringData = $otherTest->lowering_test_load || $otherTest->lowering_impact_speed || 
                       $otherTest->lowering_result || $otherTest->lowering_method || 
                       $otherTest->lowering_distance || $otherTest->lowering_duration || 
                       $otherTest->lowering_cycles || $otherTest->brake_efficiency || 
                       $otherTest->control_response || $otherTest->lowering_notes;
    echo "  - Should be pre-selected: " . ($hasLoweringData ? 'YES' : 'NO') . "\n\n";
    
    echo "=== SUMMARY ===\n";
    echo "✅ Other Services should be selected: " . ($hasOtherServices ? 'YES' : 'NO') . "\n";
    echo "✅ Drop Test should be pre-selected: " . ($hasDropData ? 'YES' : 'NO') . "\n";
    echo "✅ Tilt Test should be pre-selected: " . ($hasTiltData ? 'YES' : 'NO') . "\n";
    echo "✅ Lowering Test should be pre-selected: " . ($hasLoweringData ? 'YES' : 'NO') . "\n";
    
    // Output JSON for JavaScript debugging
    echo "\n=== JSON DATA FOR JAVASCRIPT ===\n";
    echo json_encode($otherTest->toArray(), JSON_PRETTY_PRINT) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}