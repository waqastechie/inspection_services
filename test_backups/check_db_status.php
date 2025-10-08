<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

use Illuminate\Support\Facades\Schema;
use App\Models\Inspection;
use App\Models\Client;
use App\Models\User;

echo "=== DATABASE STATUS CHECK ===\n";

// Check table existence
echo "Table Status:\n";
echo "- inspection_services: " . (Schema::hasTable('inspection_services') ? 'EXISTS' : 'DROPPED') . "\n";
echo "- lifting_examinations: " . (Schema::hasTable('lifting_examinations') ? 'EXISTS' : 'MISSING') . "\n";
echo "- mpi_inspections: " . (Schema::hasTable('mpi_inspections') ? 'EXISTS' : 'MISSING') . "\n";
echo "- inspections: " . (Schema::hasTable('inspections') ? 'EXISTS' : 'MISSING') . "\n";
echo "- clients: " . (Schema::hasTable('clients') ? 'EXISTS' : 'MISSING') . "\n";
echo "- users: " . (Schema::hasTable('users') ? 'EXISTS' : 'MISSING') . "\n";

echo "\nData Counts:\n";
try {
    echo "- Inspections: " . Inspection::count() . "\n";
    echo "- Clients: " . Client::count() . "\n";
    echo "- Users: " . User::count() . "\n";
} catch (Exception $e) {
    echo "Error counting records: " . $e->getMessage() . "\n";
}

echo "\n=== STATUS COMPLETE ===\n";
