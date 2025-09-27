<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING SPECIFIC AREAS THAT MIGHT CAUSE THE ERROR ===\n";

try {
    // Test 1: Personnel Controller directly
    echo "1. Testing PersonnelController methods...\n";
    
    $controller = new \App\Http\Controllers\PersonnelController();
    
    echo "2. Testing Personnel Model queries...\n";
    
    // Test different queries that might be causing the issue
    $queries = [
        function() { return \App\Models\Personnel::count(); },
        function() { return \App\Models\Personnel::where('is_active', true)->count(); },
        function() { return \App\Models\Personnel::active()->count(); },
        function() { return \App\Models\Personnel::where('employee_id', 'EMP001')->count(); },
    ];
    
    foreach ($queries as $i => $query) {
        try {
            $result = $query();
            echo "   Query " . ($i + 1) . ": ✓ Success (result: {$result})\n";
        } catch (Exception $e) {
            echo "   Query " . ($i + 1) . ": ✗ Error: " . $e->getMessage() . "\n";
            if (strpos($e->getMessage(), 'personnel') !== false) {
                echo "     ^^ THIS IS THE ERROR WE'RE LOOKING FOR!\n";
            }
        }
    }
    
    echo "3. Testing if Model has correct table name...\n";
    $personnel = new \App\Models\Personnel();
    echo "   Personnel model table: " . $personnel->getTable() . "\n";
    
    echo "4. Testing Personnel relationships...\n";
    try {
        // Test if any relationships might be causing issues
        $first = \App\Models\Personnel::first();
        if ($first) {
            echo "   Found personnel record: " . $first->first_name . " " . $first->last_name . "\n";
        } else {
            echo "   No personnel records found\n";
        }
    } catch (Exception $e) {
        echo "   Relationship test error: " . $e->getMessage() . "\n";
    }
    
    echo "5. Testing specific employee_id lookup...\n";
    
    // Test the specific pattern that was failing
    try {
        $count = \App\Models\Personnel::where('employee_id', '123')->count();
        echo "   Personnel with employee_id = '123': {$count}\n";
    } catch (Exception $e) {
        echo "   Error with employee_id = '123': " . $e->getMessage() . "\n";
        if (strpos($e->getMessage(), 'personnel') !== false && strpos($e->getMessage(), "doesn't exist") !== false) {
            echo "     ^^ FOUND THE CULPRIT! This query is using wrong table name\n";
        }
    }
    
    try {
        $count = \App\Models\Personnel::where('employee_id', 123)->count();
        echo "   Personnel with employee_id = 123 (integer): {$count}\n";
    } catch (Exception $e) {
        echo "   Error with employee_id = 123 (integer): " . $e->getMessage() . "\n";
        if (strpos($e->getMessage(), 'personnel') !== false && strpos($e->getMessage(), "doesn't exist") !== false) {
            echo "     ^^ FOUND THE CULPRIT! This query is using wrong table name\n";
        }
    }
    
} catch (Exception $e) {
    echo "GENERAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";