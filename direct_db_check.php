<?php

// Check database connection and show results
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

use Illuminate\Support\Facades\DB;

echo "=== DIRECT DATABASE CHECK ===\n";

try {
    // Check database connection
    $connection = DB::connection();
    $dbName = $connection->getDatabaseName();
    echo "Connected to database: $dbName\n\n";
    
    // Check users table
    $users = DB::table('users')->get();
    echo "USERS TABLE (" . count($users) . " records):\n";
    foreach ($users as $user) {
        echo "- ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: " . ($user->role ?? 'none') . "\n";
    }
    echo "\n";
    
    // Check clients table
    $clients = DB::table('clients')->get();
    echo "CLIENTS TABLE (" . count($clients) . " records):\n";
    foreach ($clients as $client) {
        echo "- ID: {$client->id}, Name: {$client->name}, Email: {$client->email}\n";
    }
    echo "\n";
    
    // Check inspections table
    $inspections = DB::table('inspections')->get();
    echo "INSPECTIONS TABLE (" . count($inspections) . " records):\n";
    foreach ($inspections as $inspection) {
        echo "- ID: {$inspection->id}, Number: {$inspection->inspection_number}, Status: {$inspection->status}\n";
    }
    echo "\n";
    
    // Check if tables exist
    echo "TABLE EXISTENCE CHECK:\n";
    $tables = ['users', 'clients', 'inspections', 'lifting_examinations', 'mpi_inspections'];
    foreach ($tables as $table) {
        $exists = DB::getSchemaBuilder()->hasTable($table);
        echo "- $table: " . ($exists ? "EXISTS" : "MISSING") . "\n";
    }
    
    if (count($users) > 0) {
        echo "\n✅ DATABASE IS POPULATED!\n";
        echo "\n🔑 You can login with:\n";
        echo "- admin@inspectionservices.com / admin123 (Super Admin)\n";
        echo "- admin@company.com / password (Admin)\n";
        echo "- inspector@company.com / password (Inspector)\n";
    } else {
        echo "\n❌ DATABASE IS EMPTY!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}

echo "\n=== CHECK COMPLETE ===\n";
