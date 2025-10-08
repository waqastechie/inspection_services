<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== HUNTING FOR THE PERSONNEL TABLE ISSUE ===\n";

try {
    // Enable query logging
    DB::enableQueryLog();
    
    echo "Testing the exact query that was failing...\n";
    
    // Try both versions to see which one fails
    echo "1. Testing with personnels (correct): ";
    try {
        $result1 = DB::select('select count(*) as aggregate from personnels where employee_id = ?', [123]);
        echo "✓ SUCCESS - " . json_encode($result1) . "\n";
    } catch (Exception $e) {
        echo "✗ FAILED - " . $e->getMessage() . "\n";
    }
    
    echo "2. Testing with personnel (incorrect): ";
    try {
        $result2 = DB::select('select count(*) as aggregate from personnel where employee_id = ?', [123]);
        echo "✓ SUCCESS (unexpected!) - " . json_encode($result2) . "\n";
    } catch (Exception $e) {
        echo "✗ FAILED (expected) - " . $e->getMessage() . "\n";
    }
    
    // Check what queries were logged
    $queries = DB::getQueryLog();
    echo "\n3. Query log:\n";
    foreach ($queries as $i => $query) {
        echo "   Query " . ($i + 1) . ": " . $query['sql'] . "\n";
        echo "   Bindings: " . json_encode($query['bindings']) . "\n";
    }
    
    // Show actual table structure
    echo "\n4. Actual table info:\n";
    $tables = DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        $tableName = array_values((array) $table)[0];
        if (strpos($tableName, 'personnel') !== false) {
            echo "   Found table: {$tableName}\n";
        }
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";