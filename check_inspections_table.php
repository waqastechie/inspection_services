<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== INSPECTIONS TABLE STRUCTURE ===\n\n";

try {
    $columns = DB::select('DESCRIBE inspections');
    
    echo "Field Name | Type | Null | Default\n";
    echo "-----------|------|------|--------\n";
    
    foreach ($columns as $column) {
        echo sprintf("%-20s | %-15s | %-5s | %s\n", 
            $column->Field, 
            $column->Type, 
            $column->Null, 
            $column->Default ?: 'NULL'
        );
    }
    
    echo "\n=== CHECKING FOR SERVICE-RELATED FIELDS ===\n";
    
    $serviceFields = [];
    foreach ($columns as $column) {
        if (stripos($column->Field, 'service') !== false) {
            $serviceFields[] = $column->Field;
        }
    }
    
    if (empty($serviceFields)) {
        echo "❌ No service-related fields found in inspections table\n";
    } else {
        echo "✅ Service-related fields found:\n";
        foreach ($serviceFields as $field) {
            echo "  - {$field}\n";
        }
    }
    
    echo "\n=== CHECKING INSPECTION 7 DATA ===\n";
    
    $inspection = DB::table('inspections')->where('id', 7)->first();
    
    if (!$inspection) {
        echo "❌ Inspection 7 not found\n";
    } else {
        echo "✅ Inspection 7 found\n";
        
        // Check service-related fields
        foreach ($serviceFields as $field) {
            $value = $inspection->$field;
            echo "  {$field}: " . ($value ?: 'NULL') . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";

?>