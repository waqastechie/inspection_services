<?php
echo "=== SIMPLE TEST ===\n";
echo "PHP is working!\n";
echo "Current time: " . date('Y-m-d H:i:s') . "\n";

try {
    require_once 'vendor/autoload.php';
    echo "Autoload works!\n";
    
    $app = require_once 'bootstrap/app.php';
    echo "Laravel bootstrap works!\n";
    
    $app->boot();
    echo "Laravel boot works!\n";
    
    $connection = \Illuminate\Support\Facades\DB::connection();
    echo "DB connection works!\n";
    
    $dbName = $connection->getDatabaseName();
    echo "Database name: $dbName\n";
    
    $userCount = \Illuminate\Support\Facades\DB::table('users')->count();
    echo "Users in database: $userCount\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "=== END TEST ===\n";
?>
