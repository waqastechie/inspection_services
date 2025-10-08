<?php
require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Checking inspection ID 7 data for Other Tests...\n\n";

try {
    // First, check if inspection ID 7 exists
    $inspection = DB::table('inspections')->where('id', 7)->first();
    
    if (!$inspection) {
        echo "❌ Inspection ID 7 does not exist in the database.\n";
        exit;
    }
    
    echo "✅ Inspection ID 7 exists.\n";
    
    // Convert to array for easier access
    $inspectionData = (array) $inspection;
    echo "Basic info: ID: {$inspectionData['id']}, Created: {$inspectionData['created_at']}\n\n";
    
    // Check all Other Tests related columns
    $otherTestsColumns = [
        // Drop Test columns
        'drop_test_load',
        'drop_type', 
        'drop_distance',
        'drop_suspended',
        'drop_impact_speed',
        'drop_result',
        'drop_notes',
        
        // Tilt Test columns
        'tilt_test_load',
        'loaded_tilt',
        'empty_tilt',
        'tilt_results',
        'tilt_stability',
        'tilt_direction',
        'tilt_duration',
        'tilt_notes',
        
        // Lowering Test columns
        'lowering_test_load',
        'lowering_impact_speed',
        'lowering_result',
        'lowering_method',
        'lowering_distance',
        'lowering_duration',
        'lowering_cycles',
        'brake_efficiency',
        'control_response',
        'lowering_notes',
        
        // Legacy thorough examination column
        'thorough_examination_comments'
    ];
    
    echo "=== OTHER TESTS DATA FOR INSPECTION ID 7 ===\n";
    
    $hasData = false;
    
    foreach ($otherTestsColumns as $column) {
        if (Schema::hasColumn('inspections', $column)) {
            $value = $inspectionData[$column] ?? 'NULL';
            $status = ($value && $value !== 'NULL' && $value !== '') ? "✓ HAS DATA" : "✗ EMPTY";
            echo "- {$column}: {$value} [{$status}]\n";
            
            if ($value && $value !== 'NULL' && $value !== '') {
                $hasData = true;
            }
        } else {
            echo "- {$column}: COLUMN MISSING\n";
        }
    }
    
    echo "\n=== SUMMARY ===\n";
    if ($hasData) {
        echo "✅ Inspection ID 7 has some Other Tests data saved.\n";
    } else {
        echo "❌ Inspection ID 7 has NO Other Tests data saved.\n";
    }
    
    // Also check if there are any non-null values in the entire row
    echo "\n=== ALL NON-NULL COLUMNS FOR INSPECTION ID 7 ===\n";
    $allColumns = Schema::getColumnListing('inspections');
    $nonNullData = [];
    
    foreach ($allColumns as $column) {
        $value = $inspectionData[$column] ?? null;
        if ($value !== null && $value !== '') {
            $nonNullData[$column] = $value;
        }
    }
    
    echo "Total non-null columns: " . count($nonNullData) . "\n";
    foreach ($nonNullData as $column => $value) {
        $displayValue = strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;
        echo "- {$column}: {$displayValue}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}