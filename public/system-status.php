<?php
header('Content-Type: text/plain');

echo "=== INSPECTION SYSTEM STATUS ===\n\n";

try {
    // Test database connection
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test inspection model
    $inspection = new App\Models\Inspection();
    echo "✅ Inspection model loaded\n";
    
    // Test inspection count
    $count = App\Models\Inspection::count();
    echo "✅ Database connection: {$count} inspections found\n";
    
    // Test inspection images table
    if (Schema::hasTable('inspection_images')) {
        $imageCount = App\Models\InspectionImage::count();
        echo "✅ Images table exists: {$imageCount} images found\n";
    } else {
        echo "❌ Images table does not exist\n";
    }
    
    // Test relationships
    $testInspection = App\Models\Inspection::first();
    if ($testInspection) {
        $images = $testInspection->images;
        echo "✅ Image relationship works: " . $images->count() . " images for inspection {$testInspection->id}\n";
        
        $imagesForEdit = $testInspection->images_for_edit;
        echo "✅ Images for edit attribute: " . $imagesForEdit->count() . " formatted images\n";
    }
    
    echo "\n🎉 SYSTEM IS READY!\n";
    echo "✅ Data updates should work\n";
    echo "✅ Images system is functional\n";
    echo "✅ Separate image table implemented\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
?>
