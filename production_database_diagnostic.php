<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

echo "=== PRODUCTION DATABASE DIAGNOSTIC ===\n\n";

try {
    // Check database connection
    echo "✅ Database connection: ";
    $pdo = DB::connection()->getPdo();
    echo "Connected\n\n";
    
    // Check if inspections table exists
    $tables = DB::select("SHOW TABLES");
    $tableNames = array_column($tables, 'Tables_in_' . env('DB_DATABASE', 'inspection_services'));
    
    echo "📋 Tables in database:\n";
    foreach ($tableNames as $table) {
        echo "  - $table\n";
    }
    echo "\n";
    
    // Check inspections table structure
    if (in_array('inspections', $tableNames)) {
        echo "🔍 Inspections table columns:\n";
        $columns = DB::select("DESCRIBE inspections");
        
        $requiredColumns = [
            'area_of_examination', 'services_performed', 'contract', 'work_order',
            'purchase_order', 'client_job_reference', 'job_ref', 'standards',
            'local_procedure_number', 'drawing_number', 'test_restrictions',
            'surface_condition', 'inspector_comments', 'next_inspection_date',
            'lifting_examination_inspector', 'load_test_inspector',
            'thorough_examination_inspector', 'mpi_service_inspector', 'visual_inspector'
        ];
        
        $existingColumns = array_column($columns, 'Field');
        
        echo "  Existing columns:\n";
        foreach ($existingColumns as $col) {
            echo "    ✅ $col\n";
        }
        
        echo "\n  Missing columns:\n";
        $missingColumns = array_diff($requiredColumns, $existingColumns);
        
        if (empty($missingColumns)) {
            echo "    ✅ All required columns present!\n";
        } else {
            foreach ($missingColumns as $col) {
                echo "    ❌ $col\n";
            }
        }
        
    } else {
        echo "❌ Inspections table not found!\n";
    }
    
    // Check migration status
    echo "\n📊 Migration status:\n";
    try {
        $migrations = DB::table('migrations')->orderBy('batch')->get();
        foreach ($migrations as $migration) {
            echo "  ✅ {$migration->migration}\n";
        }
    } catch (Exception $e) {
        echo "  ❌ Cannot read migrations table: " . $e->getMessage() . "\n";
    }
    
    // Test a simple inspection creation
    echo "\n🧪 Testing inspection creation:\n";
    try {
        $testData = [
            'inspection_number' => 'TEST' . time(),
            'client_name' => 'Test Client',
            'project_name' => 'Test Project',
            'location' => 'Test Location',
            'inspection_date' => date('Y-m-d'),
            'lead_inspector_name' => 'Test Inspector',
            'lead_inspector_certification' => 'Test Cert',
            'equipment_type' => 'Test Equipment',
            'status' => 'draft'
        ];
        
        $inspection = App\Models\Inspection::create($testData);
        echo "  ✅ Basic inspection creation works (ID: {$inspection->id})\n";
        
        // Test with additional fields
        $inspection->update([
            'area_of_examination' => 'Test Area',
            'services_performed' => 'Test Services',
            'inspector_comments' => 'Test Comments'
        ]);
        echo "  ✅ Additional fields update works\n";
        
        // Clean up
        $inspection->delete();
        echo "  ✅ Cleanup completed\n";
        
    } catch (Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
