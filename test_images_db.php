<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Database Test for Inspection Images ===\n";

// Check if table exists
if (Schema::hasTable('inspection_images')) {
    echo "✓ inspection_images table exists\n";
    
    // Check table structure
    $columns = Schema::getColumnListing('inspection_images');
    echo "Columns: " . implode(', ', $columns) . "\n";
    
    // Count total images
    $totalImages = DB::table('inspection_images')->count();
    echo "Total images in database: " . $totalImages . "\n";
    
    // Check images for inspection 47
    $imagesFor47 = DB::table('inspection_images')->where('inspection_id', 47)->get();
    echo "Images for inspection 47: " . $imagesFor47->count() . "\n";
    
    if ($imagesFor47->count() > 0) {
        foreach ($imagesFor47 as $image) {
            echo "  - ID: {$image->id}, Name: {$image->original_name}, Path: {$image->file_path}\n";
        }
    }
    
} else {
    echo "✗ inspection_images table does not exist\n";
}

// Check if storage directory exists
$storageDir = storage_path('app/public/inspections/images');
if (is_dir($storageDir)) {
    echo "✓ Storage directory exists: $storageDir\n";
    $files = glob($storageDir . '/*');
    echo "Files in storage: " . count($files) . "\n";
} else {
    echo "✗ Storage directory does not exist: $storageDir\n";
}

echo "=== Test Complete ===\n";
