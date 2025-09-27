<?php
// Simple script to add database columns
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "Checking database connection...\n";

try {
    // Test connection
    $tables = DB::select('SHOW TABLES');
    echo "Database connected successfully. Found " . count($tables) . " tables.\n";
    
    // Check if columns exist
    $columns = Schema::getColumnListing('inspections');
    echo "Current columns in inspections table: " . implode(', ', $columns) . "\n";
    
    // Add columns if they don't exist
    if (!in_array('created_by', $columns)) {
        Schema::table('inspections', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
        });
        echo "Added created_by column\n";
    } else {
        echo "created_by column already exists\n";
    }
    
    if (!in_array('completed_at', $columns)) {
        Schema::table('inspections', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable();
        });
        echo "Added completed_at column\n";
    } else {
        echo "completed_at column already exists\n";
    }
    
    if (!in_array('completed_by', $columns)) {
        Schema::table('inspections', function (Blueprint $table) {
            $table->unsignedBigInteger('completed_by')->nullable();
        });
        echo "Added completed_by column\n";
    } else {
        echo "completed_by column already exists\n";
    }
    
    // Show final columns
    $finalColumns = Schema::getColumnListing('inspections');
    echo "Final columns: " . implode(', ', $finalColumns) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
