<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=sc', 'root', '');
    $stmt = $pdo->query('DESCRIBE inspection_equipment');
    
    echo "inspection_equipment Table Structure:\n";
    echo "Field | Type | Null | Default\n";
    echo "------|------|------|--------\n";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . ' | ' . $row['Type'] . ' | ' . $row['Null'] . ' | ' . ($row['Default'] ?: 'NULL') . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
