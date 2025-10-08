<?php
require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== OTHER TESTS TABLE STRUCTURE ===\n";
try {
    $columns = DB::select("DESCRIBE other_tests");
    foreach ($columns as $column) {
        echo "Field: {$column->Field}, Type: {$column->Type}, Null: {$column->Null}, Default: {$column->Default}\n";
    }
} catch (Exception $e) {
    echo "Error describing table: " . $e->getMessage() . "\n";
}

echo "\n=== OTHER TESTS DATA FOR INSPECTION 7 ===\n";
try {
    $otherTest = DB::table('other_tests')->where('inspection_id', 7)->first();
    if ($otherTest) {
        echo "Found other_tests record for inspection 7:\n";
        foreach ((array)$otherTest as $key => $value) {
            echo "$key: " . ($value ?? 'NULL') . "\n";
        }
    } else {
        echo "No other_tests record found for inspection 7\n";
    }
} catch (Exception $e) {
    echo "Error querying other_tests: " . $e->getMessage() . "\n";
}

echo "\n=== CHECK ALL OTHER TESTS RECORDS ===\n";
try {
    $allOtherTests = DB::table('other_tests')->get();
    echo "Total other_tests records: " . $allOtherTests->count() . "\n";
    foreach ($allOtherTests as $test) {
        echo "ID: {$test->id}, Inspection ID: {$test->inspection_id}\n";
    }
} catch (Exception $e) {
    echo "Error querying all other_tests: " . $e->getMessage() . "\n";
}