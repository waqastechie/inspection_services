<?php
require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Inspection;

echo "=== TESTING OTHER TESTS CLICKABILITY AND FORMS ===\n\n";

// Load inspection 7 with otherTest relationship
$inspection = Inspection::with('otherTest')->find(7);

if (!$inspection) {
    echo "❌ Inspection ID 7 not found!\n";
    exit;
}

echo "✅ Inspection ID 7 loaded successfully\n";

if (!$inspection->otherTest) {
    echo "❌ No otherTest relationship found!\n";
    exit;
}

echo "✅ otherTest relationship loaded successfully\n\n";

// Check the data that should trigger pre-selection
$otherTest = $inspection->otherTest;

echo "=== CHECKING PRE-SELECTION DATA ===\n";

// Drop Test data check
$dropTestFields = [
    'drop_test_load', 'drop_type', 'drop_distance', 'drop_suspended',
    'drop_impact_speed', 'drop_result', 'drop_notes'
];

$dropHasData = false;
echo "Drop Test fields:\n";
foreach ($dropTestFields as $field) {
    $value = $otherTest->$field ?? 'NULL';
    echo "  - $field: $value\n";
    if (!empty($otherTest->$field)) {
        $dropHasData = true;
    }
}
echo "Drop Test should be pre-selected: " . ($dropHasData ? 'YES' : 'NO') . "\n\n";

// Tilt Test data check
$tiltTestFields = [
    'tilt_test_load', 'loaded_tilt', 'empty_tilt', 'tilt_results',
    'tilt_stability', 'tilt_direction', 'tilt_duration', 'tilt_notes'
];

$tiltHasData = false;
echo "Tilt Test fields:\n";
foreach ($tiltTestFields as $field) {
    $value = $otherTest->$field ?? 'NULL';
    echo "  - $field: $value\n";
    if (!empty($otherTest->$field)) {
        $tiltHasData = true;
    }
}
echo "Tilt Test should be pre-selected: " . ($tiltHasData ? 'YES' : 'NO') . "\n\n";

// Lowering Test data check
$loweringTestFields = [
    'lowering_test_load', 'lowering_impact_speed', 'lowering_result', 'lowering_method',
    'lowering_distance', 'lowering_duration', 'lowering_cycles', 'brake_efficiency',
    'control_response', 'lowering_notes'
];

$loweringHasData = false;
echo "Lowering Test fields:\n";
foreach ($loweringTestFields as $field) {
    $value = $otherTest->$field ?? 'NULL';
    echo "  - $field: $value\n";
    if (!empty($otherTest->$field)) {
        $loweringHasData = true;
    }
}
echo "Lowering Test should be pre-selected: " . ($loweringHasData ? 'YES' : 'NO') . "\n\n";

echo "=== JAVASCRIPT DEBUG INFORMATION ===\n";
echo "Add this to browser console to debug:\n\n";

echo "console.log('=== OTHER TESTS DEBUG ===');\n";
echo "console.log('Drop Test should be pre-selected:', " . ($dropHasData ? 'true' : 'false') . ");\n";
echo "console.log('Tilt Test should be pre-selected:', " . ($tiltHasData ? 'true' : 'false') . ");\n";
echo "console.log('Lowering Test should be pre-selected:', " . ($loweringHasData ? 'true' : 'false') . ");\n\n";

echo "// Check if cards are pre-selected\n";
echo "console.log('Drop Test card selected:', document.querySelector('[data-sub-service=\"drop-test\"]').classList.contains('selected'));\n";
echo "console.log('Tilt Test card selected:', document.querySelector('[data-sub-service=\"tilt-test\"]').classList.contains('selected'));\n";
echo "console.log('Lowering Test card selected:', document.querySelector('[data-sub-service=\"lowering-test\"]').classList.contains('selected'));\n\n";

echo "// Check if forms are displayed\n";
echo "console.log('Drop Test form visible:', document.getElementById('dropTestFormSection').style.display !== 'none');\n";
echo "console.log('Tilt Test form visible:', document.getElementById('tiltTestFormSection').style.display !== 'none');\n";
echo "console.log('Lowering Test form visible:', document.getElementById('loweringTestFormSection').style.display !== 'none');\n\n";

echo "// Test clicking on cards\n";
echo "console.log('Testing click on Drop Test card...');\n";
echo "document.querySelector('[data-sub-service=\"drop-test\"]').click();\n";
echo "console.log('Drop Test form after click:', document.getElementById('dropTestFormSection').style.display !== 'none');\n\n";

echo "=== FORM SECTIONS CHECK ===\n";
echo "Check if these form section files exist:\n";
echo "1. resources/views/inspections/sections/drop-test.blade.php\n";
echo "2. resources/views/inspections/sections/tilt-test.blade.php\n";
echo "3. resources/views/inspections/sections/lowering-test.blade.php\n\n";

// Check if form section files exist
$formSections = [
    'drop-test' => 'resources/views/inspections/sections/drop-test.blade.php',
    'tilt-test' => 'resources/views/inspections/sections/tilt-test.blade.php',
    'lowering-test' => 'resources/views/inspections/sections/lowering-test.blade.php'
];

foreach ($formSections as $name => $path) {
    if (file_exists($path)) {
        echo "✅ $name form section exists: $path\n";
    } else {
        echo "❌ $name form section MISSING: $path\n";
    }
}

echo "\n=== NEXT STEPS ===\n";
echo "1. Open the edit wizard for inspection 7, step 2\n";
echo "2. Open browser developer tools (F12)\n";
echo "3. Go to Console tab\n";
echo "4. Paste the JavaScript debug code above\n";
echo "5. Check if cards are clickable and forms appear\n";
?>