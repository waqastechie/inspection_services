<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=inspection_services', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking foreign key constraints for inspection_services table...\n\n";
    
    // Check what tables reference inspection_services
    $stmt = $pdo->query("
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM 
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE 
            REFERENCED_TABLE_SCHEMA = 'inspection_services' 
            AND REFERENCED_TABLE_NAME = 'inspection_services'
    ");
    
    $constraints = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($constraints)) {
        echo "No foreign key constraints found referencing inspection_services table.\n";
    } else {
        echo "Foreign key constraints referencing inspection_services:\n";
        foreach ($constraints as $constraint) {
            echo "- Table: {$constraint['TABLE_NAME']}, Column: {$constraint['COLUMN_NAME']}, Constraint: {$constraint['CONSTRAINT_NAME']}\n";
        }
    }
    
    echo "\n";
    
    // Check if inspection_services table exists and has data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM inspection_services");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Records in inspection_services table: " . $result['count'] . "\n";
    
    // Show table structure
    echo "\nInspection_services table structure:\n";
    $stmt = $pdo->query("DESCRIBE inspection_services");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}