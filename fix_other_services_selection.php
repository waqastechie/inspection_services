<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inspection;

echo "=== FIXING OTHER SERVICES SELECTION ===\n\n";

$inspectionId = 7;
$inspection = Inspection::find($inspectionId);

if (!$inspection) {
    echo "❌ Inspection {$inspectionId} not found!\n";
    exit(1);
}

echo "📋 Inspection ID: {$inspectionId}\n";
echo "📅 Date: {$inspection->inspection_date}\n";
echo "🏢 Client: {$inspection->client_name}\n\n";

// Check current services performed
echo "=== CURRENT STATE ===\n";
$currentServicesPerformed = $inspection->services_performed;
echo "Current services_performed: " . var_export($currentServicesPerformed, true) . "\n";

// Decode if it's a string
if (is_string($currentServicesPerformed)) {
    $servicesArray = json_decode($currentServicesPerformed, true);
} else {
    $servicesArray = $currentServicesPerformed ?: [];
}

echo "Decoded services_performed: " . var_export($servicesArray, true) . "\n";

// Check if Other Services is already included
$hasOtherServices = false;
if (is_array($servicesArray)) {
    $hasOtherServices = in_array('Other Services', $servicesArray) || 
                       in_array('other_services', $servicesArray) ||
                       in_array('Other Tests', $servicesArray) ||
                       in_array('other_tests', $servicesArray);
}

echo "Has 'Other Services': " . ($hasOtherServices ? "YES" : "NO") . "\n\n";

if ($hasOtherServices) {
    echo "✅ 'Other Services' is already in services_performed. No update needed.\n";
} else {
    echo "=== UPDATING SERVICES PERFORMED ===\n";
    
    // Ensure we have an array
    if (!is_array($servicesArray)) {
        $servicesArray = [];
    }
    
    // Add 'Other Services' to the array
    $servicesArray[] = 'Other Services';
    
    // Remove duplicates
    $servicesArray = array_unique($servicesArray);
    
    echo "New services_performed array: " . var_export($servicesArray, true) . "\n";
    
    // Update the inspection
    $inspection->services_performed = json_encode($servicesArray);
    $saved = $inspection->save();
    
    if ($saved) {
        echo "✅ Successfully updated services_performed!\n";
        echo "📝 'Other Services' has been added to the services performed.\n";
    } else {
        echo "❌ Failed to update services_performed!\n";
        exit(1);
    }
}

echo "\n=== VERIFICATION ===\n";
// Reload the inspection to verify
$inspection = $inspection->fresh();
$updatedServicesPerformed = $inspection->services_performed;
echo "Updated services_performed: " . var_export($updatedServicesPerformed, true) . "\n";

if (is_string($updatedServicesPerformed)) {
    $updatedArray = json_decode($updatedServicesPerformed, true);
    echo "Decoded updated services_performed: " . var_export($updatedArray, true) . "\n";
    
    $hasOtherServicesNow = in_array('Other Services', $updatedArray);
    echo "Contains 'Other Services' now: " . ($hasOtherServicesNow ? "YES" : "NO") . "\n";
    
    if ($hasOtherServicesNow) {
        echo "\n🎉 SUCCESS! The Other Services functionality should now work correctly:\n";
        echo "   ✅ Other Services card will be selected\n";
        echo "   ✅ Sub-services will be pre-selected based on existing data\n";
        echo "   ✅ Form sections will be displayed properly\n";
    }
}

echo "\n=== NEXT STEPS ===\n";
echo "1. 🌐 Refresh the inspection edit page in your browser\n";
echo "2. 🔍 Verify that 'Other Services' card is selected\n";
echo "3. ✅ Check that Drop Test, Tilt Test, and Lowering Test are pre-selected\n";
echo "4. 📝 Test form functionality and data saving\n";

echo "\n=== COMPLETE ===\n";

?>