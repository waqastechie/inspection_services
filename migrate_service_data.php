<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Database configuration from Laravel
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => env('DB_CONNECTION', 'mysql'),
    'host' => env('DB_HOST', 'localhost'),
    'database' => env('DB_DATABASE', 'sc'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "Starting data migration from inspections table to service-specific tables...\n";

try {
    // Get all inspections with service data
    $inspections = DB::table('inspections')->get();
    
    echo "Found " . count($inspections) . " inspections to process.\n";
    
    $mpiCount = 0;
    $liftingCount = 0;
    $loadTestCount = 0;
    $otherTestCount = 0;
    
    foreach ($inspections as $inspection) {
        echo "Processing inspection ID: {$inspection->id}\n";
        
        // Migrate MPI Inspection data
        $mpiData = [
            'inspection_id' => $inspection->id,
            'mpi_service_inspector' => $inspection->mpi_service_inspector,
            'visual_inspector' => $inspection->visual_inspector,
            'visual_comments' => $inspection->visual_comments ?? null,
            'visual_method' => $inspection->visual_method ?? null,
            'visual_lighting' => $inspection->visual_lighting ?? null,
            'visual_equipment' => $inspection->visual_equipment ?? null,
            'visual_conditions' => $inspection->visual_conditions ?? null,
            'visual_results' => $inspection->visual_results ?? null,
            'created_at' => $inspection->created_at,
            'updated_at' => $inspection->updated_at,
        ];
        
        // Only insert if there's actual MPI data
        if ($inspection->mpi_service_inspector || $inspection->visual_inspector || 
            $inspection->visual_comments || $inspection->visual_method) {
            
            // Check if record already exists
            $existingMpi = DB::table('mpi_inspections')
                ->where('inspection_id', $inspection->id)
                ->first();
                
            if (!$existingMpi) {
                DB::table('mpi_inspections')->insert($mpiData);
                $mpiCount++;
                echo "  - Created MPI inspection record\n";
            } else {
                echo "  - MPI inspection record already exists\n";
            }
        }
        
        // Migrate Lifting Examination data
        $liftingData = [
            'inspection_id' => $inspection->id,
            'lifting_examination_inspector' => $inspection->lifting_examination_inspector,
            'thorough_examination_inspector' => $inspection->thorough_examination_inspector,
            'thorough_examination_comments' => $inspection->thorough_examination_comments ?? null,
            'thorough_method' => $inspection->thorough_method ?? null,
            'thorough_equipment' => $inspection->thorough_equipment ?? null,
            'thorough_conditions' => $inspection->thorough_conditions ?? null,
            'thorough_results' => $inspection->thorough_results ?? null,
            'created_at' => $inspection->created_at,
            'updated_at' => $inspection->updated_at,
        ];
        
        // Only insert if there's actual lifting examination data
        if ($inspection->lifting_examination_inspector || $inspection->thorough_examination_inspector || 
            $inspection->thorough_examination_comments) {
            
            // Check if record already exists
            $existingLifting = DB::table('lifting_examinations')
                ->where('inspection_id', $inspection->id)
                ->first();
                
            if (!$existingLifting) {
                DB::table('lifting_examinations')->insert($liftingData);
                $liftingCount++;
                echo "  - Created lifting examination record\n";
            } else {
                echo "  - Lifting examination record already exists\n";
            }
        }
        
        // Migrate Load Test data
        $loadTestData = [
            'inspection_id' => $inspection->id,
            'load_test_inspector' => $inspection->load_test_inspector,
            'load_test_duration' => $inspection->load_test_duration ?? null,
            'load_test_two_points_diagonal' => $inspection->load_test_two_points_diagonal ?? null,
            'load_test_four_points' => $inspection->load_test_four_points ?? null,
            'load_test_deflection' => $inspection->load_test_deflection ?? null,
            'load_test_deformation' => $inspection->load_test_deformation ?? null,
            'load_test_distance_from_ground' => $inspection->load_test_distance_from_ground ?? null,
            'load_test_result' => $inspection->load_test_result ?? null,
            'load_test_notes' => $inspection->load_test_notes ?? null,
            'created_at' => $inspection->created_at,
            'updated_at' => $inspection->updated_at,
        ];
        
        // Only insert if there's actual load test data
        if ($inspection->load_test_inspector || $inspection->load_test_duration || 
            $inspection->load_test_result || $inspection->load_test_notes) {
            
            // Check if record already exists
            $existingLoadTest = DB::table('load_tests')
                ->where('inspection_id', $inspection->id)
                ->first();
                
            if (!$existingLoadTest) {
                DB::table('load_tests')->insert($loadTestData);
                $loadTestCount++;
                echo "  - Created load test record\n";
            } else {
                echo "  - Load test record already exists\n";
            }
        }
        
        // Migrate Other Tests data
        $otherTestData = [
            'inspection_id' => $inspection->id,
            'drop_test_load' => $inspection->drop_test_load ?? null,
            'tilt_test_load' => $inspection->tilt_test_load ?? null,
            'lowering_test_load' => $inspection->lowering_test_load ?? null,
            'thorough_examination_comments' => $inspection->thorough_examination_comments ?? null,
            'created_at' => $inspection->created_at,
            'updated_at' => $inspection->updated_at,
        ];
        
        // Only insert if there's actual other test data
        if ($inspection->drop_test_load || $inspection->tilt_test_load || 
            $inspection->lowering_test_load) {
            
            // Check if record already exists
            $existingOtherTest = DB::table('other_tests')
                ->where('inspection_id', $inspection->id)
                ->first();
                
            if (!$existingOtherTest) {
                DB::table('other_tests')->insert($otherTestData);
                $otherTestCount++;
                echo "  - Created other test record\n";
            } else {
                echo "  - Other test record already exists\n";
            }
        }
    }
    
    echo "\n=== Migration Summary ===\n";
    echo "Total inspections processed: " . count($inspections) . "\n";
    echo "MPI inspection records created: {$mpiCount}\n";
    echo "Lifting examination records created: {$liftingCount}\n";
    echo "Load test records created: {$loadTestCount}\n";
    echo "Other test records created: {$otherTestCount}\n";
    echo "\nData migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}