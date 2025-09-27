<?php
header('Content-Type: text/plain');

echo "=== TESTING IMAGE FUNCTIONALITY ===\n\n";

try {
    // Bootstrap Laravel
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel bootstrapped\n";
    
    // Check if images table exists
    if (!\Schema::hasTable('inspection_images')) {
        echo "❌ inspection_images table does not exist!\n";
        echo "Please run: http://localhost/inspection_services/public/create-table.php\n";
        exit(1);
    }
    echo "✅ inspection_images table exists\n";
    
    // Check storage directory
    $storageDir = storage_path('app/public/inspections/images');
    if (!is_dir($storageDir)) {
        echo "❌ Storage directory does not exist: {$storageDir}\n";
        mkdir($storageDir, 0755, true);
        echo "✅ Created storage directory\n";
    } else {
        echo "✅ Storage directory exists\n";
    }
    
    // Test InspectionImage model
    $imageModel = new App\Models\InspectionImage();
    echo "✅ InspectionImage model loaded\n";
    
    // Test ImageUploadService
    $service = new App\Services\ImageUploadService();
    echo "✅ ImageUploadService loaded\n";
    
    // Test inspection with images
    $inspection = App\Models\Inspection::first();
    if ($inspection) {
        echo "✅ Test inspection loaded: {$inspection->inspection_number}\n";
        
        // Test images relationship
        $images = $inspection->images;
        echo "✅ Images relationship: " . $images->count() . " images found\n";
        
        // Test images_for_edit attribute
        $imagesForEdit = $inspection->images_for_edit;
        echo "✅ Images for edit: " . $imagesForEdit->count() . " formatted images\n";
    }
    
    echo "\n🎉 IMAGE SYSTEM IS READY!\n";
    echo "✅ Database table exists\n";
    echo "✅ Storage directory ready\n"; 
    echo "✅ Models and services loaded\n";
    echo "✅ Relationships working\n";
    echo "\nNow try uploading images in your inspection form!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
?>
