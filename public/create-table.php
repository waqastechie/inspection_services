<?php
header('Content-Type: text/plain');

echo "Creating inspection_images table using direct MySQL connection...\n\n";

try {
    // Database connection details from .env
    $host = '127.0.0.1';
    $port = '3306';
    $database = 'sc';
    $username = 'root';
    $password = '';
    
    // Create connection
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful\n";
    
    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'inspection_images'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table 'inspection_images' already exists\n";
        
        // Show table structure
        $stmt = $pdo->query("DESCRIBE inspection_images");
        echo "\nTable structure:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  {$row['Field']} - {$row['Type']}\n";
        }
        exit;
    }
    
    echo "Creating inspection_images table...\n";
    
    // SQL to create table
    $sql = "CREATE TABLE `inspection_images` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `inspection_id` bigint(20) unsigned NOT NULL,
        `original_name` varchar(255) NOT NULL,
        `file_name` varchar(255) NOT NULL,
        `file_path` varchar(500) NOT NULL,
        `mime_type` varchar(100) NOT NULL,
        `file_size` bigint(20) unsigned NOT NULL,
        `caption` text DEFAULT NULL,
        `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
        `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `inspection_images_inspection_id_foreign` (`inspection_id`),
        KEY `inspection_images_inspection_id_sort_order_index` (`inspection_id`,`sort_order`),
        CONSTRAINT `inspection_images_inspection_id_foreign` FOREIGN KEY (`inspection_id`) REFERENCES `inspections` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    
    echo "✅ Table created successfully!\n";
    
    // Verify table was created
    $stmt = $pdo->query("SHOW TABLES LIKE 'inspection_images'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table verification successful\n";
        
        // Show table structure
        $stmt = $pdo->query("DESCRIBE inspection_images");
        echo "\nTable structure:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  {$row['Field']} - {$row['Type']}\n";
        }
    } else {
        echo "❌ Table verification failed\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
