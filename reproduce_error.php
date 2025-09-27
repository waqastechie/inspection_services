<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== TRYING TO REPRODUCE THE EXACT ERROR ===\n";

try {
    // Enable query logging to catch any problematic queries
    DB::enableQueryLog();
    
    echo "1. Testing Personnel model methods that might trigger the error:\n";
    
    // Test different ways to query Personnel
    $tests = [
        'Personnel::count()' => function() { return \App\Models\Personnel::count(); },
        'Personnel::all()' => function() { return \App\Models\Personnel::all()->count(); },
        'Personnel::find(123)' => function() { return \App\Models\Personnel::find(123); },
        'Personnel::where(employee_id, 123)' => function() { return \App\Models\Personnel::where('employee_id', 123)->first(); },
        'Personnel::where(employee_id, "123")' => function() { return \App\Models\Personnel::where('employee_id', '123')->first(); },
        'Personnel with relationships' => function() { 
            $p = \App\Models\Personnel::first(); 
            if ($p) {
                // Try to load any relationships that might exist
                return $p->load('personnelAssignments');
            }
            return null;
        },
        'Inspection with personnel relationships' => function() {
            $inspection = \App\Models\Inspection::first();
            if ($inspection) {
                return $inspection->personnelAssignments;
            }
            return collect();
        },
    ];
    
    foreach ($tests as $name => $test) {
        try {
            echo "   Testing: {$name}... ";
            $result = $test();
            echo "✓ Success\n";
        } catch (Exception $e) {
            echo "✗ ERROR: " . $e->getMessage() . "\n";
            if (strpos($e->getMessage(), 'personnel') !== false && strpos($e->getMessage(), "doesn't exist") !== false) {
                echo "      ^^ THIS IS THE ERROR WE'RE LOOKING FOR!\n";
                echo "      Full error: " . $e->getMessage() . "\n";
                echo "      File: " . $e->getFile() . ":" . $e->getLine() . "\n";
            }
        }
    }
    
    echo "\n2. Testing route access that might trigger the error:\n";
    
    // Test some routes that might trigger the error
    echo "   Testing personnel API route...\n";
    try {
        $controller = new \App\Http\Controllers\PersonnelController();
        $request = new \Illuminate\Http\Request();
        $response = $controller->getPersonnel($request);
        echo "   ✓ Personnel API works\n";
    } catch (Exception $e) {
        echo "   ✗ Personnel API ERROR: " . $e->getMessage() . "\n";
        if (strpos($e->getMessage(), 'personnel') !== false) {
            echo "      ^^ FOUND THE PROBLEM!\n";
        }
    }
    
    echo "\n3. Checking for cached queries or prepared statements:\n";
    $queries = DB::getQueryLog();
    echo "   Total queries logged: " . count($queries) . "\n";
    
    foreach ($queries as $i => $query) {
        if (strpos($query['sql'], 'personnel') !== false) {
            echo "   Query " . ($i + 1) . ": " . $query['sql'] . "\n";
            echo "   Bindings: " . json_encode($query['bindings']) . "\n";
            
            if (strpos($query['sql'], 'personnel') !== false && strpos($query['sql'], 'personnels') === false) {
                echo "      ^^ PROBLEMATIC QUERY FOUND!\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "MAJOR ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    if (strpos($e->getMessage(), 'personnel') !== false && strpos($e->getMessage(), "doesn't exist") !== false) {
        echo "\n🎯 FOUND THE EXACT ERROR!\n";
        echo "This is the same error you're seeing.\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";