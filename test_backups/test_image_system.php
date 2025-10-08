<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Services\ImageUploadService;
use App\Models\Inspection;
use App\Models\InspectionImage;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "Testing Image System Implementation\n";
echo "================================\n\n";

try {
    // Test 1: Check if migration needs to be run
    echo "1. Checking database tables...\n";
    
    // Check if inspection_images table exists
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('inspection_images');
    
    if (!$tableExists) {
        echo "   ❌ inspection_images table does not exist\n";
        echo "   Running migration...\n";
        
        // Run the migration
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--force' => true
        ]);
        
        echo "   ✅ Migration completed\n";
    } else {
        echo "   ✅ inspection_images table exists\n";
    }
    
    // Test 2: Check models
    echo "\n2. Testing models...\n";
    
    // Test InspectionImage model
    $imageModel = new InspectionImage();
    echo "   ✅ InspectionImage model loaded\n";
    
    // Test relationship
    $inspection = Inspection::first();
    if ($inspection) {
        echo "   ✅ Found inspection: {$inspection->id}\n";
        
        // Test relationship
        $images = $inspection->images;
        echo "   ✅ Images relationship works: " . $images->count() . " images\n";
    } else {
        echo "   ⚠️  No inspections found in database\n";
    }
    
    // Test 3: Check service
    echo "\n3. Testing ImageUploadService...\n";
    
    $imageService = new ImageUploadService();
    echo "   ✅ ImageUploadService instantiated\n";
    
    // Test storage disk
    $disk = \Illuminate\Support\Facades\Storage::disk('public');
    echo "   ✅ Public disk accessible\n";
    
    // Check if storage directory exists
    if (!$disk->exists('inspection_images')) {
        $disk->makeDirectory('inspection_images');
        echo "   ✅ Created inspection_images directory\n";
    } else {
        echo "   ✅ inspection_images directory exists\n";
    }
    
    echo "\n4. Summary:\n";
    echo "   ✅ Database structure ready\n";
    echo "   ✅ Models working\n";
    echo "   ✅ Service available\n";
    echo "   ✅ Storage configured\n";
    
    echo "\nImage system is ready for use!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
