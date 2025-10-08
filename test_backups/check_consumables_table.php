<?php
// Check consumables table structure
$host = 'localhost';
$dbname = 'sc';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking consumables table structure:\n";
    $stmt = $pdo->prepare("DESCRIBE consumables");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Available columns:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
    echo "\nChecking ConsumableController query...\n";
    
    // Check what the controller is trying to select
    echo "Controller tries to select: id, name, type, brand_manufacturer, quantity_available, unit\n";
    echo "Missing columns: ";
    
    $available_columns = array_column($columns, 'Field');
    $required_columns = ['id', 'name', 'type', 'brand_manufacturer', 'quantity_available', 'unit'];
    $missing = array_diff($required_columns, $available_columns);
    
    if (empty($missing)) {
        echo "None - all columns exist\n";
    } else {
        echo implode(', ', $missing) . "\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>