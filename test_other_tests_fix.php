<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inspection;

try {
    echo "=== TESTING OTHER TESTS FIX ===\n\n";
    
    // Load inspection 7 with the same relationships as editWizardMain
    $inspection = Inspection::with([
        'client',
        'personnelAssignments.personnel',
        'equipmentAssignments.equipment',
        'consumableAssignments.consumable',
        'inspectionResults',
        'images',
        'liftingExamination',
        'mpiInspection',
        'inspectionEquipment',
        'equipmentType',
        'otherTest'
    ])->find(7);
    
    if (!$inspection) {
        echo "Inspection 7 not found!\n";
        exit;
    }
    
    echo "Inspection ID: " . $inspection->id . "\n";
    echo "Project Name: " . $inspection->project_name . "\n\n";
    
    // Check if otherTest relationship is loaded
    echo "=== OTHER TEST RELATIONSHIP ===\n";
    $otherTest = $inspection->otherTest;
    
    if ($otherTest) {
        echo "✅ Other Test relationship loaded successfully!\n";
        echo "Other Test ID: " . $otherTest->id . "\n";
        echo "Inspection ID: " . $otherTest->inspection_id . "\n\n";
        
        // Check sub-service data
        echo "=== SUB-SERVICE DATA ===\n";
        
        // Drop Test
        echo "Drop Test:\n";
        echo "  - Load: " . ($otherTest->drop_test_load ?? 'NULL') . "\n";
        echo "  - Type: " . ($otherTest->drop_type ?? 'NULL') . "\n";
        echo "  - Distance: " . ($otherTest->drop_distance ?? 'NULL') . "\n";
        echo "  - Has Data: " . (!empty($otherTest->drop_test_load) ? 'YES' : 'NO') . "\n\n";
        
        // Tilt Test
        echo "Tilt Test:\n";
        echo "  - Load: " . ($otherTest->tilt_test_load ?? 'NULL') . "\n";
        echo "  - Loaded Tilt: " . ($otherTest->loaded_tilt ?? 'NULL') . "\n";
        echo "  - Empty Tilt: " . ($otherTest->empty_tilt ?? 'NULL') . "\n";
        echo "  - Has Data: " . (!empty($otherTest->tilt_test_load) ? 'YES' : 'NO') . "\n\n";
        
        // Lowering Test
        echo "Lowering Test:\n";
        echo "  - Load: " . ($otherTest->lowering_test_load ?? 'NULL') . "\n";
        echo "  - Impact Speed: " . ($otherTest->lowering_impact_speed ?? 'NULL') . "\n";
        echo "  - Method: " . ($otherTest->lowering_method ?? 'NULL') . "\n";
        echo "  - Has Data: " . (!empty($otherTest->lowering_test_load) ? 'YES' : 'NO') . "\n\n";
        
        // Check what should be pre-selected
        echo "=== PRE-SELECTION STATUS ===\n";
        $dropHasData = !empty($otherTest->drop_test_load);
        $tiltHasData = !empty($otherTest->tilt_test_load);
        $loweringHasData = !empty($otherTest->lowering_test_load);
        
        echo "Drop Test should be pre-selected: " . ($dropHasData ? 'YES' : 'NO') . "\n";
        echo "Tilt Test should be pre-selected: " . ($tiltHasData ? 'YES' : 'NO') . "\n";
        echo "Lowering Test should be pre-selected: " . ($loweringHasData ? 'YES' : 'NO') . "\n";
        
        if ($dropHasData && $tiltHasData && $loweringHasData) {
            echo "\n✅ All sub-services should be pre-selected!\n";
        } else {
            echo "\n⚠️ Some sub-services may not be pre-selected due to missing data.\n";
        }
        
    } else {
        echo "❌ Other Test relationship is NULL!\n";
        echo "This means no other_tests record exists for inspection 7.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}