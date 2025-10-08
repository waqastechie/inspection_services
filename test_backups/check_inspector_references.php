<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING FOR PROBLEMATIC INSPECTOR REFERENCES ===\n";

try {
    echo "1. Checking inspections table for inspector IDs with value 123:\n";
    
    $inspectorFields = [
        'lifting_examination_inspector',
        'load_test_inspector', 
        'thorough_examination_inspector',
        'mpi_service_inspector',
        'visual_inspector'
    ];
    
    foreach ($inspectorFields as $field) {
        $count = DB::table('inspections')->where($field, 123)->count();
        echo "   {$field}: {$count} records\n";
        
        if ($count > 0) {
            echo "   ⚠️  Found records with {$field} = 123 - this could be the problem!\n";
            $records = DB::table('inspections')->where($field, 123)->select('id', $field)->get();
            foreach ($records as $record) {
                echo "      Inspection ID: {$record->id}\n";
            }
        }
    }
    
    echo "\n2. Checking PersonnelAssignment table:\n";
    $personnelAssignments = DB::table('personnel_assignments')->where('personnel_id', 123)->count();
    echo "   personnel_assignments with personnel_id = 123: {$personnelAssignments}\n";
    
    echo "\n3. Checking if Personnel ID 123 exists:\n";
    $personnelExists = DB::table('personnels')->where('id', 123)->exists();
    echo "   Personnel with ID 123 exists: " . ($personnelExists ? 'YES' : 'NO') . "\n";
    
    if ($personnelExists) {
        $personnel = DB::table('personnels')->where('id', 123)->first();
        echo "   Personnel: {$personnel->first_name} {$personnel->last_name}\n";
    }
    
    echo "\n4. Testing relationship loading that might trigger the error:\n";
    
    // Try to load an inspection with these relationships
    $inspection = \App\Models\Inspection::first();
    if ($inspection) {
        echo "   Testing inspection ID: {$inspection->id}\n";
        
        try {
            $inspector = $inspection->liftingExaminationInspector;
            echo "   ✓ liftingExaminationInspector loaded successfully\n";
        } catch (Exception $e) {
            echo "   ✗ liftingExaminationInspector ERROR: " . $e->getMessage() . "\n";
            if (strpos($e->getMessage(), 'personnel') !== false) {
                echo "      ^^ THIS IS THE PROBLEM!\n";
            }
        }
        
        try {
            $inspector = $inspection->loadTestInspector;
            echo "   ✓ loadTestInspector loaded successfully\n";
        } catch (Exception $e) {
            echo "   ✗ loadTestInspector ERROR: " . $e->getMessage() . "\n";
            if (strpos($e->getMessage(), 'personnel') !== false) {
                echo "      ^^ THIS IS THE PROBLEM!\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    if (strpos($e->getMessage(), 'personnel') !== false && strpos($e->getMessage(), "doesn't exist") !== false) {
        echo "\n🔍 FOUND THE PROBLEM! This is the query causing the issue.\n";
    }
}

echo "\n=== CHECK COMPLETE ===\n";