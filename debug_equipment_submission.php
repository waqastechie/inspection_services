<?php
// Debug script to understand form submission flow
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "=== DEBUGGING EQUIPMENT FORM SUBMISSION ===\n\n";
    
    // 1. Check the current step 4 form JavaScript functionality
    $step4File = 'resources/views/inspections/wizard/step4.blade.php';
    if (file_exists($step4File)) {
        echo "✅ Step 4 blade file exists\n";
        
        // Look for the step4SaveAndContinue function
        $content = file_get_contents($step4File);
        if (strpos($content, 'window.step4SaveAndContinue') !== false) {
            echo "✅ step4SaveAndContinue function found\n";
        } else {
            echo "❌ step4SaveAndContinue function NOT found\n";
        }
        
        if (strpos($content, 'equipment_data') !== false) {
            echo "✅ equipment_data handling found\n";
        } else {
            echo "❌ equipment_data handling NOT found\n";
        }
    } else {
        echo "❌ Step 4 blade file not found\n";
    }
    
    // 2. Check the saveWizardStep method in InspectionController
    $controllerFile = 'app/Http/Controllers/InspectionController.php';
    if (file_exists($controllerFile)) {
        echo "✅ InspectionController exists\n";
        
        $content = file_get_contents($controllerFile);
        if (strpos($content, 'saveEquipmentData') !== false) {
            echo "✅ saveEquipmentData method found\n";
        } else {
            echo "❌ saveEquipmentData method NOT found\n";
        }
        
        // Check if step 4 equipment handling exists
        if (strpos($content, 'step == 4') !== false && strpos($content, 'equipment_data') !== false) {
            echo "✅ Step 4 equipment handling found\n";
        } else {
            echo "❌ Step 4 equipment handling NOT found\n";
        }
    }
    
    // 3. Test the equipment data collection format
    echo "\n=== TESTING EQUIPMENT DATA FORMAT ===\n";
    
    // Simulate what the JavaScript should collect
    $sampleEquipmentData = [
        [
            'temp_id' => 'row_1',
            'category' => 'items',
            'equipment_type' => 'Wire Rope',
            'serial_number' => 'WR-001',
            'description' => 'Test wire rope',
            'swl' => '1000 Kg',
            'test_load_applied' => '1250 Kg',
            'reason_for_examination' => 'Periodic Inspection',
            'date_of_manufacture' => '2023-01-15',
            'date_of_last_examination' => '2024-01-15',
            'date_of_next_examination' => '2025-01-15',
            'status' => 'ND',
            'remarks' => 'Good condition'
        ]
    ];
    
    $jsonData = json_encode($sampleEquipmentData);
    echo "Sample equipment data JSON:\n" . $jsonData . "\n\n";
    
    // Test if this would decode properly
    $decoded = json_decode($jsonData, true);
    if (is_array($decoded) && count($decoded) > 0) {
        echo "✅ JSON decodes properly\n";
        echo "   Items count: " . count($decoded) . "\n";
        echo "   First item equipment_type: " . $decoded[0]['equipment_type'] . "\n";
    } else {
        echo "❌ JSON decode failed\n";
    }
    
    // 4. Check if there are any recent wizard submissions for inspection 5
    echo "\n=== CHECKING WIZARD SUBMISSION LOGS ===\n";
    
    $inspection = \App\Models\Inspection::find(5);
    echo "Inspection 5 last updated: " . $inspection->updated_at . "\n";
    echo "Current status: " . $inspection->status . "\n";
    
    // Check if there are any activity logs or related data
    if (\Schema::hasTable('activity_logs')) {
        $recentLogs = \DB::table('activity_logs')
            ->where('subject_type', 'App\\Models\\Inspection')
            ->where('subject_id', 5)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        if ($recentLogs->count() > 0) {
            echo "Recent activity logs:\n";
            foreach ($recentLogs as $log) {
                echo "  - " . $log->created_at . ": " . $log->description . "\n";
            }
        } else {
            echo "No activity logs found for inspection 5\n";
        }
    }
    
    echo "\n=== RECOMMENDATIONS ===\n";
    echo "1. Check if the wizard form is submitting equipment_data parameter\n";
    echo "2. Verify that step 4 JavaScript is collecting table data properly\n";
    echo "3. Test the form submission with browser developer tools\n";
    echo "4. Check if saveWizardStep method is being called with step=4\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}