<?php

// Simple database check
$db = new PDO('sqlite:database/database.sqlite');

// Check if table exists
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='inspection_images'")->fetchAll();

if (count($tables) > 0) {
    echo "✓ inspection_images table exists\n";
    
    // Count total images
    $totalCount = $db->query("SELECT COUNT(*) FROM inspection_images")->fetchColumn();
    echo "Total images: $totalCount\n";
    
    // Check for inspection 47
    $stmt = $db->prepare("SELECT * FROM inspection_images WHERE inspection_id = ?");
    $stmt->execute([47]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Images for inspection 47: " . count($images) . "\n";
    foreach ($images as $image) {
        echo "  - ID: {$image['id']}, Name: {$image['original_name']}, Path: {$image['file_path']}\n";
    }
    
} else {
    echo "✗ inspection_images table does not exist\n";
}
