<?php
$database = 'c:\xampp\htdocs\inspection_services\database\database.sqlite';

try {
    $pdo = new PDO('sqlite:' . $database);
    
    echo "Database Tables and Record Counts:\n";
    echo "==================================\n";
    
    $tables = ['users', 'clients', 'personnels', 'equipment', 'consumables', 'inspections'];
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM {$table}");
            $count = $stmt->fetchColumn();
            echo "{$table}: {$count} records\n";
        } catch (Exception $e) {
            echo "{$table}: Error - " . $e->getMessage() . "\n";
        }
    }
    
    // Check users specifically
    echo "\nUsers in database:\n";
    $stmt = $pdo->query("SELECT name, email, role FROM users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['name']} ({$row['email']}) - {$row['role']}\n";
    }
    
} catch (Exception $e) {
    echo "Database connection error: " . $e->getMessage() . "\n";
}
