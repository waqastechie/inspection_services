<?php
// Simple database check
$dbPath = __DIR__ . '/database/database.sqlite';

if (!file_exists($dbPath)) {
    echo "Database file not found!\n";
    exit;
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Database Seeding Verification ===\n\n";
    
    // Check record counts
    $tables = [
        'users' => 'Users',
        'clients' => 'Clients', 
        'personnels' => 'Personnel',
        'equipment' => 'Equipment',
        'consumables' => 'Consumables',
        'inspections' => 'Inspections'
    ];
    
    foreach ($tables as $table => $label) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM {$table}");
        $count = $stmt->fetchColumn();
        echo "{$label}: {$count}\n";
    }
    
    echo "\n=== Users ===\n";
    $stmt = $pdo->query("SELECT name, email, role FROM users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['name']} ({$row['email']}) - {$row['role']}\n";
    }
    
    echo "\n=== Inspections with Client Info ===\n";
    $stmt = $pdo->query("
        SELECT i.inspection_number, c.client_name, i.project_name, i.status, i.qa_status 
        FROM inspections i 
        LEFT JOIN clients c ON i.client_id = c.id 
        LIMIT 3
    ");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['inspection_number']}: {$row['client_name']} - {$row['project_name']} (Status: {$row['status']}, QA: {$row['qa_status']})\n";
    }
    
    echo "\n✅ Database seeding verification complete!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
