<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

echo "=== INSPECTION DATA SAVING TEST ===\n\n";

try {
    // Test 1: Check required columns exist
    echo "1. Checking database columns...\n";
    $columns = DB::select("SHOW COLUMNS FROM inspections");
    $columnNames = array_column($columns, 'Field');
    
    $requiredColumns = [
        'area_of_examination', 'services_performed', 'contract', 'work_order',
        'purchase_order', 'client_job_reference', 'job_ref', 'standards',
        'local_procedure_number', 'drawing_number', 'test_restrictions',
        'surface_condition', 'inspector_comments', 'next_inspection_date',
        'lifting_examination_inspector', 'load_test_inspector',
        'thorough_examination_inspector', 'mpi_service_inspector', 'visual_inspector'
    ];
    
    $missingColumns = array_diff($requiredColumns, $columnNames);
    
    if (empty($missingColumns)) {
        echo "✅ All required columns present!\n\n";
    } else {
        echo "❌ Missing columns:\n";
        foreach ($missingColumns as $col) {
            echo "   - $col\n";
        }
        echo "\n";
    }
    
    // Test 2: Create a test inspection with all fields
    echo "2. Testing inspection creation with full data...\n";
    
    $testData = [
        'inspection_number' => 'TEST' . time(),
        'client_name' => 'Test Client Full',
        'project_name' => 'Test Project Full',
        'location' => 'Test Location Full',
        'area_of_examination' => 'Test Area',
        'services_performed' => 'Test Services',
        'contract' => 'TEST-CONTRACT-001',
        'work_order' => 'WO-001',
        'purchase_order' => 'PO-001',
        'client_job_reference' => 'CJR-001',
        'job_ref' => 'JR-001',
        'standards' => 'Test Standards',
        'local_procedure_number' => 'LPN-001',
        'drawing_number' => 'DWG-001',
        'test_restrictions' => 'Test restrictions',
        'surface_condition' => 'Good condition',
        'inspection_date' => date('Y-m-d'),
        'lead_inspector_name' => 'Test Inspector Full',
        'lead_inspector_certification' => 'Test Certification Full',
        'equipment_type' => 'Test Equipment Full',
        'status' => 'draft',
        'inspector_comments' => 'Test comments',
        'next_inspection_date' => date('Y-m-d', strtotime('+1 year'))
    ];
    
    $inspection = App\Models\Inspection::create($testData);
    echo "✅ Basic inspection created (ID: {$inspection->id})\n";
    
    // Test 3: Test service inspector assignments
    if (empty($missingColumns)) {
        echo "\n3. Testing service inspector assignments...\n";
        
        // Check if we have personnel
        $personnel = App\Models\Personnel::first();
        if ($personnel) {
            $inspection->update([
                'lifting_examination_inspector' => $personnel->id,
                'load_test_inspector' => $personnel->id,
            ]);
            echo "✅ Service inspectors assigned successfully\n";
        } else {
            echo "⚠️  No personnel found for assignment test\n";
        }
    }
    
    // Test 4: Verify data was saved
    echo "\n4. Verifying saved data...\n";
    $savedInspection = App\Models\Inspection::find($inspection->id);
    
    $fieldsToCheck = [
        'area_of_examination', 'services_performed', 'contract', 'work_order',
        'inspector_comments', 'next_inspection_date'
    ];
    
    $savedFields = 0;
    foreach ($fieldsToCheck as $field) {
        if (!empty($savedInspection->$field)) {
            $savedFields++;
            echo "✅ $field: {$savedInspection->$field}\n";
        } else {
            echo "❌ $field: NOT SAVED\n";
        }
    }
    
    echo "\n📊 RESULTS:\n";
    echo "Saved fields: $savedFields/" . count($fieldsToCheck) . "\n";
    
    if ($savedFields == count($fieldsToCheck)) {
        echo "✅ ALL DATA SAVING CORRECTLY!\n\n";
        echo "💡 Your inspection form should now work properly.\n";
        echo "   Try creating a new inspection through the web interface.\n";
    } else {
        echo "❌ SOME DATA NOT SAVING\n\n";
        echo "💡 Possible issues:\n";
        echo "   - Missing database columns\n";
        echo "   - Mass assignment protection\n";
        echo "   - Validation errors\n";
    }
    
    // Clean up
    $inspection->delete();
    echo "\n✅ Test inspection cleaned up\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "File: " . $e->getFile() . "\n";
}
