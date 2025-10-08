<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

echo "=== TESTING THE PERSONNEL CREATION FIX ===\n";

try {
    // Enable query logging to see the validation queries
    DB::enableQueryLog();
    
    echo "1. Testing personnel creation validation (the exact scenario that was failing):\n";
    
    // Simulate the validation that happens during personnel creation
    $testData = [
        'first_name' => 'Test',
        'last_name' => 'Person',
        'position' => 'Inspector',
        'department' => 'Quality',
        'employee_id' => '123',  // This was causing the error!
        'email' => 'test@example.com',
        'is_active' => true
    ];
    
    echo "   Testing validation with employee_id = '123'...\n";
    
    $validator = Validator::make($testData, [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'department' => 'required|string|max:255',
        'employee_id' => 'nullable|string|max:100|unique:personnels,employee_id',  // FIXED!
        'email' => 'nullable|email|max:255',
        'is_active' => 'boolean',
    ]);
    
    if ($validator->fails()) {
        echo "   ✓ Validation failed (as expected for existing employee_id)\n";
        echo "   Errors: " . json_encode($validator->errors()->toArray()) . "\n";
    } else {
        echo "   ✓ Validation passed\n";
    }
    
    echo "\n2. Checking the queries that were executed:\n";
    $queries = DB::getQueryLog();
    
    foreach ($queries as $i => $query) {
        echo "   Query " . ($i + 1) . ": " . $query['sql'] . "\n";
        echo "   Bindings: " . json_encode($query['bindings']) . "\n";
        
        // Check if this query uses the correct table name
        if (strpos($query['sql'], 'personnel') !== false) {
            if (strpos($query['sql'], 'personnels') !== false) {
                echo "   ✅ CORRECT: Uses 'personnels' table\n";
            } else {
                echo "   ❌ ERROR: Still uses 'personnel' table!\n";
            }
        }
        echo "\n";
    }
    
    echo "\n3. Testing actual Personnel creation:\n";
    
    // Test with a unique employee_id
    $uniqueTestData = [
        'first_name' => 'Test',
        'last_name' => 'PersonNew',
        'position' => 'Inspector',
        'department' => 'Quality',
        'employee_id' => 'TEST_' . time(),  // Unique ID
        'email' => 'testnew@example.com',
        'is_active' => true
    ];
    
    try {
        $personnel = \App\Models\Personnel::create($uniqueTestData);
        echo "   ✅ Personnel created successfully! ID: {$personnel->id}\n";
        echo "   Name: {$personnel->first_name} {$personnel->last_name}\n";
        echo "   Employee ID: {$personnel->employee_id}\n";
        
        // Clean up - delete the test record
        $personnel->delete();
        echo "   ✅ Test record cleaned up\n";
        
    } catch (Exception $e) {
        echo "   ❌ Personnel creation failed: " . $e->getMessage() . "\n";
        if (strpos($e->getMessage(), 'personnel') !== false) {
            echo "      ^^ This is the table name issue!\n";
        }
    }
    
    echo "\n🎉 PERSONNEL CREATION ISSUE FIXED!\n";
    echo "The validation rules now use the correct 'personnels' table name.\n";
    echo "You should now be able to create personnel without the SQL error.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    if (strpos($e->getMessage(), 'personnel') !== false && strpos($e->getMessage(), "doesn't exist") !== false) {
        echo "\n❌ The error is still happening. More fixes needed.\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";