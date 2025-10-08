<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Checking Inspections Table Schema ===\n\n";

try {
    // Get all columns in the inspections table
    $columns = Schema::getColumnListing('inspections');
    
    echo "Total columns in inspections table: " . count($columns) . "\n\n";
    
    // Check for Other Tests columns specifically
    $otherTestsColumns = [
        'drop_test_load', 'drop_type', 'drop_distance', 'drop_suspended', 'drop_impact_speed', 'drop_result', 'drop_notes',
        'tilt_test_load', 'loaded_tilt', 'empty_tilt', 'tilt_results', 'tilt_stability', 'tilt_direction', 'tilt_duration', 'tilt_notes',
        'lowering_test_load', 'lowering_impact_speed', 'lowering_result', 'lowering_method', 'lowering_distance', 'lowering_duration', 'lowering_cycles', 'brake_efficiency', 'control_response', 'lowering_notes',
        'thorough_examination_comments'
    ];
    
    echo "=== Other Tests Columns Status ===\n";
    $missingColumns = [];
    
    foreach ($otherTestsColumns as $column) {
        if (in_array($column, $columns)) {
            echo "✅ {$column}: EXISTS\n";
        } else {
            echo "❌ {$column}: MISSING\n";
            $missingColumns[] = $column;
        }
    }
    
    if (!empty($missingColumns)) {
        echo "\n=== MISSING COLUMNS ===\n";
        echo "The following columns are missing from the inspections table:\n";
        foreach ($missingColumns as $column) {
            echo "- {$column}\n";
        }
        
        echo "\nThis explains why the data is not being saved!\n";
        echo "You need to create a migration to add these columns.\n";
    } else {
        echo "\n✅ All Other Tests columns exist in the database.\n";
    }
    
    echo "\n=== All Columns in Inspections Table ===\n";
    foreach ($columns as $column) {
        echo "- {$column}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}