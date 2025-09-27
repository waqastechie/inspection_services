<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check database connection
try {
    $tables = DB::select('SHOW TABLES');
    echo "Database connection successful!\n";
    echo "Existing tables:\n";
    foreach ($tables as $table) {
        foreach ($table as $key => $value) {
            echo "- $value\n";
        }
    }
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
