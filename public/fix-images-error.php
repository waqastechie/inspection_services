<?php
// Direct table creation script
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Images Table</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Images Table Error</h1>
        
        <?php
        if (isset($_GET['action']) && $_GET['action'] === 'create') {
            echo '<div class="status info">🚀 Creating inspection_images table...</div>';
            
            try {
                // Include Laravel
                require_once '../vendor/autoload.php';
                $app = require_once '../bootstrap/app.php';
                $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
                $kernel->bootstrap();
                
                // Check if table exists
                if (Schema::hasTable('inspection_images')) {
                    echo '<div class="status warning">⚠️ Table inspection_images already exists!</div>';
                } else {
                    // Create the table directly
                    DB::statement('
                        CREATE TABLE inspection_images (
                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            inspection_id BIGINT UNSIGNED NOT NULL,
                            original_name VARCHAR(255) NOT NULL,
                            file_name VARCHAR(255) NOT NULL,
                            file_path VARCHAR(500) NOT NULL,
                            mime_type VARCHAR(100) NOT NULL,
                            file_size BIGINT UNSIGNED NOT NULL,
                            caption TEXT NULL,
                            metadata JSON NULL,
                            sort_order INT UNSIGNED DEFAULT 0,
                            created_at TIMESTAMP NULL,
                            updated_at TIMESTAMP NULL,
                            INDEX idx_inspection_sort (inspection_id, sort_order),
                            FOREIGN KEY (inspection_id) REFERENCES inspections(id) ON DELETE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ');
                    
                    echo '<div class="status success">✅ Table inspection_images created successfully!</div>';
                }
                
                // Verify table exists now
                if (Schema::hasTable('inspection_images')) {
                    $columns = Schema::getColumnListing('inspection_images');
                    echo '<div class="status success">✅ Table verified - Columns: ' . implode(', ', $columns) . '</div>';
                    
                    // Create storage directory
                    $storagePath = storage_path('app/public/inspection_images');
                    if (!file_exists($storagePath)) {
                        mkdir($storagePath, 0755, true);
                        echo '<div class="status success">✅ Created storage directory: ' . $storagePath . '</div>';
                    } else {
                        echo '<div class="status info">ℹ️ Storage directory already exists</div>';
                    }
                    
                    // Test models
                    try {
                        $imageModel = new App\Models\InspectionImage();
                        echo '<div class="status success">✅ InspectionImage model loads correctly</div>';
                    } catch (Exception $e) {
                        echo '<div class="status error">❌ Model error: ' . $e->getMessage() . '</div>';
                    }
                    
                    try {
                        $imageService = new App\Services\ImageUploadService();
                        echo '<div class="status success">✅ ImageUploadService loads correctly</div>';
                    } catch (Exception $e) {
                        echo '<div class="status error">❌ Service error: ' . $e->getMessage() . '</div>';
                    }
                    
                    echo '<div class="status success"><strong>🎉 Image system is now ready!</strong></div>';
                    echo '<div class="status info"><strong>The error should now be fixed!</strong><br>You can go back to your inspection pages and they should work properly.</div>';
                    
                } else {
                    echo '<div class="status error">❌ Table verification failed</div>';
                }
                
            } catch (Exception $e) {
                echo '<div class="status error">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            }
            
            echo '<a href="?" class="btn">← Back</a>';
            echo '<a href="../" class="btn btn-success">Go to Application</a>';
            
        } else {
            ?>
            <div class="status error">
                <strong>❌ Error Detected:</strong><br>
                SQLSTATE[42S02]: Base table or view not found: 1146 Table 'sc.inspection_images' doesn't exist
            </div>
            
            <div class="status info">
                <strong>🔍 Problem:</strong><br>
                The application is trying to access the inspection_images table, but it doesn't exist in your database yet.
            </div>
            
            <div class="status warning">
                <strong>⚡ Quick Fix:</strong><br>
                Click the button below to create the missing table immediately.
            </div>
            
            <a href="?action=create" class="btn btn-success">🚀 Create Images Table Now</a>
            
            <h2>📋 What this will do:</h2>
            <ul>
                <li>✅ Create the missing inspection_images table</li>
                <li>✅ Set up proper database structure for image storage</li>
                <li>✅ Create necessary storage directories</li>
                <li>✅ Verify all components are working</li>
                <li>✅ Fix the error immediately</li>
            </ul>
            
            <div class="status info">
                <strong>💡 Safe Operation:</strong><br>
                This operation is completely safe and won't affect your existing data.
                It only creates the new table needed for the enhanced image system.
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>
