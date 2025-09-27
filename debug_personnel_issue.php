<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DEBUGGING PERSONNEL TABLE ISSUE ===\n";

try {
    // Enable query logging to see exactly what queries are being run
    DB::enableQueryLog();
    
    echo "1. Testing direct table access...\n";
    
    // Test the exact query that's failing according to the error
    echo "2. Testing the failing query pattern...\n";
    echo "   Query: select count(*) as aggregate from 'personnel' where 'employee_id' = 123\n";
    
    // Try both table names to see which one works
    echo "3. Testing 'personnel' table (should fail):\n";
    try {
        $count1 = DB::table('personnel')->where('employee_id', 123)->count();
        echo "   ✓ 'personnel' table worked (unexpected!): count = {$count1}\n";
    } catch (Exception $e) {
        echo "   ✗ 'personnel' table failed (expected): " . $e->getMessage() . "\n";
    }
    
    echo "4. Testing 'personnels' table (should work):\n";
    try {
        $count2 = DB::table('personnels')->where('employee_id', 123)->count();
        echo "   ✓ 'personnels' table worked: count = {$count2}\n";
    } catch (Exception $e) {
        echo "   ✗ 'personnels' table failed (unexpected!): " . $e->getMessage() . "\n";
    }
    
    echo "5. Testing Personnel model (should work):\n";
    try {
        $count3 = \App\Models\Personnel::where('employee_id', 123)->count();
        echo "   ✓ Personnel model worked: count = {$count3}\n";
    } catch (Exception $e) {
        echo "   ✗ Personnel model failed: " . $e->getMessage() . "\n";
    }
    
    echo "6. Checking actual table structure:\n";
    $tables = DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        $tableName = array_values((array) $table)[0];
        if (strpos($tableName, 'personnel') !== false) {
            echo "   Found table: {$tableName}\n";
            
            // Get column info
            $columns = DB::select("DESCRIBE {$tableName}");
            echo "   Columns in {$tableName}:\n";
            foreach ($columns as $column) {
                echo "     - {$column->Field} ({$column->Type})\n";
            }
        }
    }
    
    echo "\n7. Checking for any queries that might still use wrong table name:\n";
    
    // Get the queries that were logged
    $queries = DB::getQueryLog();
    if (!empty($queries)) {
        echo "   Recent queries:\n";
        foreach ($queries as $query) {
            echo "     SQL: " . $query['sql'] . "\n";
            if (!empty($query['bindings'])) {
                echo "     Bindings: " . json_encode($query['bindings']) . "\n";
            }
        }
    } else {
        echo "   No queries logged\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n";