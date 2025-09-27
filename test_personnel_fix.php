<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== TESTING PERSONNEL TABLE REFERENCES ===\n";

try {
    // Test personnel table access
    echo "1. Testing personnel table access...\n";
    $personnelCount = DB::table('personnels')->count();
    echo "   ✓ Personnel table accessible. Count: {$personnelCount}\n";
    
    // Test a simple query that was previously failing
    echo "2. Testing personnel query with ID 123...\n";
    $personnel = DB::table('personnels')->where('id', 123)->first();
    if ($personnel) {
        echo "   ✓ Personnel ID 123 found: {$personnel->name}\n";
    } else {
        echo "   ! Personnel ID 123 not found (this is OK if no data)\n";
    }
    
    // Test equipment types table
    echo "3. Testing equipment types...\n";
    $equipmentTypes = DB::table('equipment_types')->count();
    echo "   ✓ Equipment types table accessible. Count: {$equipmentTypes}\n";
    
    // Test equipment table
    echo "4. Testing equipment table...\n";
    $equipment = DB::table('equipment')->count();
    echo "   ✓ Equipment table accessible. Count: {$equipment}\n";
    
    // Test equipment assignments
    echo "5. Testing equipment assignments...\n";
    $assignments = DB::table('equipment_assignments')->count();
    echo "   ✓ Equipment assignments table accessible. Count: {$assignments}\n";
    
    echo "\n=== ALL TESTS PASSED! ===\n";
    echo "The personnel table reference issue has been resolved.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== END TEST ===\n";