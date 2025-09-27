<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== MONITORING FOR PERSONNEL TABLE ERRORS ===\n";
echo "This script will monitor all database queries and catch the problematic one.\n";
echo "Please trigger the error in your browser while this script is running.\n\n";

// Enable query logging
DB::enableQueryLog();

// Set up a custom query logger to catch the problematic query
DB::listen(function ($query) {
    $sql = $query->sql;
    $bindings = $query->bindings;
    
    // Check if this is the problematic query
    if (strpos($sql, 'personnel') !== false && strpos($sql, 'personnels') === false) {
        echo "🚨 FOUND THE PROBLEMATIC QUERY! 🚨\n";
        echo "SQL: " . $sql . "\n";
        echo "Bindings: " . json_encode($bindings) . "\n";
        echo "Time: " . $query->time . "ms\n";
        
        // Get the stack trace to see where this is coming from
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);
        echo "Call Stack:\n";
        foreach ($trace as $i => $frame) {
            if (isset($frame['file']) && isset($frame['line'])) {
                echo "  #{$i} {$frame['file']}:{$frame['line']}\n";
                if (isset($frame['class']) && isset($frame['function'])) {
                    echo "      {$frame['class']}::{$frame['function']}()\n";
                } elseif (isset($frame['function'])) {
                    echo "      {$frame['function']}()\n";
                }
            }
        }
        echo "\n";
    }
    
    // Log all queries for debugging
    if (strpos($sql, 'personnel') !== false) {
        echo "Query with 'personnel': " . $sql . " [" . json_encode($bindings) . "]\n";
    }
});

echo "Query monitoring is active. Press Ctrl+C to stop.\n";
echo "Now trigger the error in your browser...\n\n";

// Keep the script running
while (true) {
    sleep(1);
    
    // Check if any new queries were logged
    $queries = DB::getQueryLog();
    if (!empty($queries)) {
        // Clear the log to avoid memory issues
        DB::flushQueryLog();
        DB::enableQueryLog();
    }
}

?>