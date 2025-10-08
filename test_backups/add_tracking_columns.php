<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Add created_by column
    DB::statement('ALTER TABLE inspections ADD COLUMN created_by BIGINT UNSIGNED NULL AFTER status');
    echo "Added created_by column\n";
} catch (Exception $e) {
    echo "created_by column might already exist: " . $e->getMessage() . "\n";
}

try {
    // Add completed_at column
    DB::statement('ALTER TABLE inspections ADD COLUMN completed_at TIMESTAMP NULL AFTER created_by');
    echo "Added completed_at column\n";
} catch (Exception $e) {
    echo "completed_at column might already exist: " . $e->getMessage() . "\n";
}

try {
    // Add completed_by column
    DB::statement('ALTER TABLE inspections ADD COLUMN completed_by BIGINT UNSIGNED NULL AFTER completed_at');
    echo "Added completed_by column\n";
} catch (Exception $e) {
    echo "completed_by column might already exist: " . $e->getMessage() . "\n";
}

echo "Done!\n";
