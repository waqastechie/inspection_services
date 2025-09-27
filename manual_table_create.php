<?php

// Connect to SQLite database directly
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');

// Create the table manually
$sql = "
CREATE TABLE IF NOT EXISTS inspection_images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    inspection_id INTEGER NOT NULL,
    original_name TEXT NOT NULL,
    file_name TEXT NOT NULL,
    file_path TEXT NOT NULL,
    mime_type TEXT NOT NULL,
    file_size INTEGER NOT NULL,
    caption TEXT,
    sort_order INTEGER DEFAULT 0,
    metadata TEXT,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (inspection_id) REFERENCES inspections(id) ON DELETE CASCADE
);
";

try {
    $db->exec($sql);
    echo "✓ Table created successfully\n";
    
    // Insert a test record
    $insertSql = "
    INSERT INTO inspection_images 
    (inspection_id, original_name, file_name, file_path, mime_type, file_size, caption, sort_order, created_at, updated_at)
    VALUES (47, 'Test Image', 'test_47_123.png', 'inspections/images/test_47_123.png', 'image/png', 1024, 'Test caption', 0, datetime('now'), datetime('now'))
    ";
    
    $db->exec($insertSql);
    echo "✓ Test record inserted\n";
    
    // Check if it was inserted
    $stmt = $db->query("SELECT * FROM inspection_images WHERE inspection_id = 47");
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Records found: " . count($images) . "\n";
    foreach ($images as $image) {
        echo "  - ID: {$image['id']}, Name: {$image['original_name']}\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
