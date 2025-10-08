<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inspection;
use App\Models\OtherTest;

echo "=== COMPREHENSIVE OTHER TESTS FUNCTIONALITY TEST ===\n\n";

// Test inspection ID 7
$inspectionId = 7;
$inspection = Inspection::find($inspectionId);

if (!$inspection) {
    echo "❌ Inspection {$inspectionId} not found!\n";
    exit(1);
}

echo "📋 Testing Inspection ID: {$inspectionId}\n";
echo "📅 Date: {$inspection->inspection_date}\n";
echo "🏢 Client: {$inspection->client_name}\n\n";

// Check service types
echo "=== SERVICE TYPES ANALYSIS ===\n";
$serviceTypes = $inspection->service_types;
echo "Raw service_types: " . var_export($serviceTypes, true) . "\n";

if (is_string($serviceTypes)) {
    $serviceTypesArray = json_decode($serviceTypes, true);
    echo "Decoded service_types: " . var_export($serviceTypesArray, true) . "\n";
} else {
    $serviceTypesArray = $serviceTypes;
}

$hasOtherServices = false;
if (is_array($serviceTypesArray)) {
    $hasOtherServices = in_array('Other Services', $serviceTypesArray) || 
                       in_array('other_services', $serviceTypesArray) ||
                       in_array('Other Tests', $serviceTypesArray) ||
                       in_array('other_tests', $serviceTypesArray);
    echo "Contains 'Other Services' or similar: " . ($hasOtherServices ? "YES" : "NO") . "\n";
} else {
    echo "⚠️  service_types is not an array!\n";
}

// Check Other Test data
echo "\n=== OTHER TEST DATA ANALYSIS ===\n";
$otherTest = OtherTest::where('inspection_id', $inspectionId)->first();

if (!$otherTest) {
    echo "❌ No OtherTest record found for inspection {$inspectionId}\n";
} else {
    echo "✅ OtherTest record found (ID: {$otherTest->id})\n\n";
    
    // Check each sub-service
    echo "🔍 DROP TEST:\n";
    echo "  - drop_test_load: " . ($otherTest->drop_test_load ?: 'NULL') . "\n";
    echo "  - drop_type: " . ($otherTest->drop_type ?: 'NULL') . "\n";
    echo "  - Should be pre-selected: " . (!empty($otherTest->drop_test_load) ? "YES" : "NO") . "\n\n";
    
    echo "🔍 TILT TEST:\n";
    echo "  - tilt_test_load: " . ($otherTest->tilt_test_load ?: 'NULL') . "\n";
    echo "  - loaded_tilt: " . ($otherTest->loaded_tilt ?: 'NULL') . "\n";
    echo "  - Should be pre-selected: " . (!empty($otherTest->tilt_test_load) ? "YES" : "NO") . "\n\n";
    
    echo "🔍 LOWERING TEST:\n";
    echo "  - lowering_test_load: " . ($otherTest->lowering_test_load ?: 'NULL') . "\n";
    echo "  - lowering_impact_speed: " . ($otherTest->lowering_impact_speed ?: 'NULL') . "\n";
    echo "  - Should be pre-selected: " . (!empty($otherTest->lowering_test_load) ? "YES" : "NO") . "\n\n";
}

// Test scenarios
echo "=== TEST SCENARIOS ===\n\n";

echo "📝 SCENARIO 1: Current State\n";
echo "  - Other Services in service_types: " . ($hasOtherServices ? "YES" : "NO") . "\n";
echo "  - OtherTest data exists: " . ($otherTest ? "YES" : "NO") . "\n";
echo "  - Expected behavior:\n";
echo "    * Other Services card should be: " . ($hasOtherServices ? "SELECTED" : "NOT SELECTED") . "\n";
if ($otherTest) {
    echo "    * Drop Test should be: " . (!empty($otherTest->drop_test_load) ? "PRE-SELECTED" : "NOT PRE-SELECTED") . "\n";
    echo "    * Tilt Test should be: " . (!empty($otherTest->tilt_test_load) ? "PRE-SELECTED" : "NOT PRE-SELECTED") . "\n";
    echo "    * Lowering Test should be: " . (!empty($otherTest->lowering_test_load) ? "PRE-SELECTED" : "NOT PRE-SELECTED") . "\n";
}

echo "\n📝 SCENARIO 2: Add 'Other Services' to service_types\n";
echo "  - This would make the Other Services card selected\n";
echo "  - Sub-services would still be pre-selected based on existing data\n";

echo "\n📝 SCENARIO 3: Remove some OtherTest data\n";
echo "  - This would test partial pre-selection\n";
echo "  - Only sub-services with data should be pre-selected\n";

// Provide JavaScript debugging info
echo "\n=== JAVASCRIPT DEBUGGING INFO ===\n";
echo "For debugging in browser console:\n";
echo "1. Check if otherTest data is loaded:\n";
echo "   console.log('otherTest data:', " . json_encode($otherTest) . ");\n\n";

echo "2. Check service selection:\n";
echo "   console.log('Service types:', " . json_encode($serviceTypesArray) . ");\n\n";

echo "3. Check pre-selection logic:\n";
echo "   console.log('Drop test should be selected:', " . (!empty($otherTest->drop_test_load) ? 'true' : 'false') . ");\n";
echo "   console.log('Tilt test should be selected:', " . (!empty($otherTest->tilt_test_load) ? 'true' : 'false') . ");\n";
echo "   console.log('Lowering test should be selected:', " . (!empty($otherTest->lowering_test_load) ? 'true' : 'false') . ");\n";

echo "\n=== RECOMMENDATIONS ===\n";
if (!$hasOtherServices && $otherTest) {
    echo "⚠️  ISSUE DETECTED: OtherTest data exists but 'Other Services' is not in service_types\n";
    echo "💡 SOLUTION: Add 'Other Services' to the service_types for this inspection\n";
    echo "🔧 COMMAND: Update inspection to include 'Other Services' in service_types\n";
}

if ($hasOtherServices && !$otherTest) {
    echo "⚠️  ISSUE DETECTED: 'Other Services' is selected but no OtherTest data exists\n";
    echo "💡 SOLUTION: Either remove 'Other Services' from service_types or add OtherTest data\n";
}

if ($hasOtherServices && $otherTest) {
    echo "✅ PERFECT: Both service selection and data exist - functionality should work correctly\n";
}

echo "\n=== TEST COMPLETE ===\n";

?>