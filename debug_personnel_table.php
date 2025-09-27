<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    // Initialize Laravel app
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "Checking database tables...\n\n";
    
    // Get all tables
    $tables = DB::select('SHOW TABLES');
    $databaseName = DB::getDatabaseName();
    
    echo "Database: $databaseName\n";
    echo "Tables found:\n";
    
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "- $tableName\n";
    }
    
    echo "\nChecking for personnel-related tables:\n";
    
    // Check if personnel table exists
    if (Schema::hasTable('personnel')) {
        echo "✅ 'personnel' table exists\n";
    } else {
        echo "❌ 'personnel' table does NOT exist\n";
    }
    
    // Check if personnels table exists
    if (Schema::hasTable('personnels')) {
        echo "✅ 'personnels' table exists\n";
    } else {
        echo "❌ 'personnels' table does NOT exist\n";
    }
    
    // Check the specific error - looking for employee_id = 123
    echo "\nTrying to find where employee_id = 123 is being used...\n";
    
    if (Schema::hasTable('personnels')) {
        $count = DB::table('personnels')->where('employee_id', 123)->count();
        echo "Found $count records in 'personnels' table with employee_id = 123\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}