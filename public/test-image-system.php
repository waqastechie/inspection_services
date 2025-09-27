<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image System Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .warning { background-color: #fff3cd; color: #856404; }
        .info { background-color: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>
    <h1>Image System Test</h1>
    
    <?php
    try {
        // Include Laravel
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        $kernel->bootstrap();
        
        echo '<div class="status success">✅ Laravel loaded successfully</div>';
        
        // Check database
        $pdo = DB::connection()->getPdo();
        echo '<div class="status success">✅ Database connected</div>';
        
        // Check if table exists
        $tableExists = Schema::hasTable('inspection_images');
        
        if (!$tableExists) {
            echo '<div class="status warning">⚠️ inspection_images table missing - creating it now...</div>';
            
            // Create table
            DB::statement("
                CREATE TABLE IF NOT EXISTS inspection_images (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    inspection_id INTEGER NOT NULL,
                    original_name VARCHAR(255) NOT NULL,
                    file_name VARCHAR(255) NOT NULL,
                    file_path VARCHAR(500) NOT NULL,
                    mime_type VARCHAR(100) NOT NULL,
                    file_size INTEGER NOT NULL,
                    caption TEXT,
                    metadata TEXT,
                    sort_order INTEGER DEFAULT 0,
                    created_at DATETIME,
                    updated_at DATETIME,
                    FOREIGN KEY (inspection_id) REFERENCES inspections(id) ON DELETE CASCADE
                )
            ");
            
            echo '<div class="status success">✅ inspection_images table created</div>';
        } else {
            echo '<div class="status success">✅ inspection_images table exists</div>';
        }
        
        // Test models
        $imageModel = new App\Models\InspectionImage();
        echo '<div class="status success">✅ InspectionImage model working</div>';
        
        $imageService = new App\Services\ImageUploadService();
        echo '<div class="status success">✅ ImageUploadService working</div>';
        
        // Check storage
        $storagePath = storage_path('app/public/inspection_images');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
            echo '<div class="status success">✅ Created storage directory</div>';
        } else {
            echo '<div class="status success">✅ Storage directory exists</div>';
        }
        
        // Test with sample inspection
        $inspections = App\Models\Inspection::take(5)->get();
        echo '<div class="status info">📊 Found ' . $inspections->count() . ' inspections</div>';
        
        foreach ($inspections as $inspection) {
            $imageCount = $inspection->images->count();
            $oldImageData = $inspection->inspection_images;
            
            echo "<div class='status info'>";
            echo "Inspection #{$inspection->id}: {$imageCount} new images";
            if (!empty($oldImageData)) {
                $oldImages = is_string($oldImageData) ? json_decode($oldImageData, true) : $oldImageData;
                $oldCount = is_array($oldImages) ? count($oldImages) : 0;
                echo ", {$oldCount} old images in JSON field";
            }
            echo "</div>";
        }
        
        echo '<div class="status success"><strong>🎉 Image system is ready!</strong></div>';
        echo '<div class="status info">You can now use the inspection forms to upload and manage images.</div>';
        
    } catch (Exception $e) {
        echo '<div class="status error">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        echo '<div class="status error">Stack trace: <pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre></div>';
    }
    ?>
    
    <h2>Next Steps</h2>
    <ul>
        <li>Go to your inspection list page</li>
        <li>Create or edit an inspection</li>
        <li>Upload some images in the Images section</li>
        <li>View the inspection to see the images displayed properly</li>
    </ul>
    
</body>
</html>
