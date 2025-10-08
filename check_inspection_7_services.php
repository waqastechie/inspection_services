<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Inspection ID 7 - Service Data Check ===\n\n";

// Check each service table
$tables = [
    'load_tests',
    'mpi_inspections', 
    'other_tests',
    'lifting_examinations',
    'visual_inspections',
    'thorough_examinations'
];

foreach ($tables as $table) {
    try {
        $count = DB::table($table)->where('inspection_id', 7)->count();
        echo "$table: $count records\n";
        
        if ($count > 0) {
            $records = DB::table($table)->where('inspection_id', 7)->get();
            foreach ($records as $record) {
                echo "  - ID: {$record->id}";
                if (isset($record->status)) {
                    echo ", Status: {$record->status}";
                }
                if (isset($record->service_type)) {
                    echo ", Service Type: {$record->service_type}";
                }
                echo "\n";
            }
        }
    } catch (Exception $e) {
        echo "$table: Table doesn't exist or error - " . $e->getMessage() . "\n";
    }
}

echo "\n=== Summary ===\n";
$totalServices = 0;
foreach ($tables as $table) {
    try {
        $count = DB::table($table)->where('inspection_id', 7)->count();
        $totalServices += $count;
    } catch (Exception $e) {
        // Ignore table errors for summary
    }
}

echo "Total service records for inspection 7: $totalServices\n";