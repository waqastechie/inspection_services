<?php

echo "=== Image System Setup and Testing ===\n\n";

// Check if we can run the inspection system
try {
    // Try to include Laravel autoloader
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "✅ Autoloader found\n";
    } else {
        echo "❌ Autoloader not found - make sure you're in the project root\n";
        exit(1);
    }
    
    // Bootstrap Laravel if possible
    if (file_exists('bootstrap/app.php')) {
        $app = require_once 'bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        $kernel->bootstrap();
        echo "✅ Laravel bootstrapped\n";
        
        // Check database connection
        try {
            $pdo = DB::connection()->getPdo();
            echo "✅ Database connected\n";
            
            // Check if inspection_images table exists
            $tableExists = Schema::hasTable('inspection_images');
            
            if (!$tableExists) {
                echo "⚠️  inspection_images table doesn't exist\n";
                echo "Creating table manually...\n";
                
                // Create the table using raw SQL
                DB::statement("
                    CREATE TABLE inspection_images (
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
                
                echo "✅ inspection_images table created\n";
            } else {
                echo "✅ inspection_images table already exists\n";
            }
            
            // Test model loading
            $imageModel = new App\Models\InspectionImage();
            echo "✅ InspectionImage model loads correctly\n";
            
            // Test service loading
            $imageService = new App\Services\ImageUploadService();
            echo "✅ ImageUploadService loads correctly\n";
            
            // Check storage directory
            $storagePath = storage_path('app/public/inspection_images');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
                echo "✅ Created storage directory: {$storagePath}\n";
            } else {
                echo "✅ Storage directory exists: {$storagePath}\n";
            }
            
            // Test a sample inspection
            $inspection = App\Models\Inspection::first();
            if ($inspection) {
                echo "✅ Found sample inspection: {$inspection->id}\n";
                
                $images = $inspection->images;
                echo "✅ Images relationship works: " . $images->count() . " images found\n";
                
                if ($images->count() > 0) {
                    foreach ($images as $image) {
                        echo "  - {$image->original_name} ({$image->formatted_size})\n";
                    }
                }
            } else {
                echo "⚠️  No inspections found in database\n";
            }
            
            echo "\n=== SUMMARY ===\n";
            echo "✅ Database table created/verified\n";
            echo "✅ Models working\n";
            echo "✅ Services working\n";
            echo "✅ Storage ready\n";
            echo "\nThe image system is now ready for use!\n";
            echo "\nYou can now:\n";
            echo "1. Upload images through the inspection form\n";
            echo "2. View images on the inspection show page\n";
            echo "3. Edit images on the inspection edit page\n";
            
        } catch (Exception $e) {
            echo "❌ Database error: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "❌ Laravel bootstrap file not found\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
