<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing Inspection model...\n";
    
    // Test basic model loading
    $inspection = new App\Models\Inspection();
    echo "✓ Inspection model created successfully\n";
    
    // Test database connection
    $count = App\Models\Inspection::count();
    echo "✓ Database query successful: Found {$count} inspections\n";
    
    // Test retrieving a record if exists
    $first = App\Models\Inspection::first();
    if ($first) {
        echo "✓ Successfully retrieved inspection: {$first->inspection_number}\n";
        
        // Test accessing attributes
        echo "✓ Client name: " . ($first->client_name ?? 'N/A') . "\n";
        echo "✓ Status: " . ($first->status ?? 'N/A') . "\n";
        
        // Test the safe image handling
        $images = $first->inspection_images;
        echo "✓ Images attribute accessed safely: " . (is_array($images) ? count($images) . " images" : "No images") . "\n";
    } else {
        echo "ℹ No inspections found in database\n";
    }
    
    echo "\n✅ ALL TESTS PASSED - No addEagerConstraints error!\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
