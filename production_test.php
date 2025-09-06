<?php

// Simple production test script
// Upload this file to your production server and run it

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Production Inspection Data Debug</h1>\n";
echo "<pre>\n";

try {
    // Include Laravel bootstrap
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    
    echo "✅ Laravel loaded successfully\n\n";
    
    // Test database connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connected\n\n";
    
    // Check inspections table structure
    echo "🔍 Checking inspections table structure...\n";
    $columns = DB::select("SHOW COLUMNS FROM inspections");
    
    $requiredColumns = [
        'area_of_examination', 'services_performed', 'contract', 'work_order',
        'purchase_order', 'client_job_reference', 'job_ref', 'standards',
        'local_procedure_number', 'drawing_number', 'test_restrictions',
        'surface_condition', 'inspector_comments', 'next_inspection_date',
        'lifting_examination_inspector', 'load_test_inspector',
        'thorough_examination_inspector', 'mpi_service_inspector', 'visual_inspector'
    ];
    
    $existingColumns = array_column($columns, 'Field');
    $missingColumns = array_diff($requiredColumns, $existingColumns);
    
    if (empty($missingColumns)) {
        echo "✅ All required columns present!\n\n";
    } else {
        echo "❌ Missing columns found:\n";
        foreach ($missingColumns as $col) {
            echo "   - $col\n";
        }
        echo "\n";
    }
    
    // Test basic inspection creation
    echo "🧪 Testing basic inspection creation...\n";
    $testData = [
        'inspection_number' => 'PRODTEST' . time(),
        'client_name' => 'Production Test Client',
        'project_name' => 'Production Test Project',
        'location' => 'Production Test Location',
        'inspection_date' => date('Y-m-d'),
        'lead_inspector_name' => 'Production Test Inspector',
        'lead_inspector_certification' => 'Test Certification',
        'equipment_type' => 'Test Equipment',
        'status' => 'draft'
    ];
    
    $inspection = App\Models\Inspection::create($testData);
    echo "✅ Basic inspection created (ID: {$inspection->id})\n";
    
    // Test additional fields if columns exist
    if (empty($missingColumns)) {
        echo "🧪 Testing additional fields...\n";
        $inspection->update([
            'area_of_examination' => 'Production Test Area',
            'services_performed' => 'Production Test Services',
            'contract' => 'TEST-001',
            'work_order' => 'WO-001',
            'inspector_comments' => 'Production test comments'
        ]);
        echo "✅ Additional fields updated successfully\n";
    }
    
    // Test service inspector assignment (if personnels table exists and has data)
    try {
        $personnel = DB::table('personnels')->first();
        if ($personnel && empty($missingColumns)) {
            echo "🧪 Testing service inspector assignment...\n";
            $inspection->update([
                'lifting_examination_inspector' => $personnel->id
            ]);
            echo "✅ Service inspector assigned successfully\n";
        } else {
            echo "⚠️  Skipping service inspector test (no personnel data or missing columns)\n";
        }
    } catch (Exception $e) {
        echo "⚠️  Personnel table issue: " . $e->getMessage() . "\n";
    }
    
    // Clean up test data
    $inspection->delete();
    echo "✅ Test inspection cleaned up\n\n";
    
    // Summary
    echo "📊 SUMMARY:\n";
    if (empty($missingColumns)) {
        echo "✅ Database structure is correct\n";
        echo "✅ Basic functionality works\n";
        echo "✅ Your issue might be:\n";
        echo "   - Form validation errors\n";
        echo "   - JavaScript issues\n";
        echo "   - Server configuration (PHP limits)\n";
        echo "   - Specific field constraints\n\n";
        echo "💡 NEXT STEPS:\n";
        echo "1. Check browser developer console for JavaScript errors\n";
        echo "2. Check Laravel logs: storage/logs/laravel.log\n";
        echo "3. Test with simple form data first\n";
    } else {
        echo "❌ Database structure issues found\n";
        echo "💡 IMMEDIATE FIX:\n";
        echo "1. Run: php artisan migrate --force\n";
        echo "2. Or manually add the missing columns\n";
        echo "3. Re-run this test\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "</pre>";
?>
