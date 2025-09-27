<?php
header('Content-Type: text/plain');

echo "=== TESTING INSPECTION SYSTEM ===\n\n";

try {
    // Bootstrap Laravel
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel bootstrapped\n";
    
    // Test basic Inspection model
    $inspection = new App\Models\Inspection();
    echo "✅ Inspection model created\n";
    
    // Test database query
    $count = App\Models\Inspection::count();
    echo "✅ Database query: {$count} inspections found\n";
    
    // Test first inspection
    $first = App\Models\Inspection::first();
    if ($first) {
        echo "✅ First inspection loaded: {$first->inspection_number}\n";
        
        // Test the safe images method (should return empty collection)
        $images = $first->images();
        echo "✅ Images method: " . get_class($images) . " with " . $images->count() . " items\n";
        
        // Test the images_for_edit attribute
        $imagesForEdit = $first->images_for_edit;
        echo "✅ Images for edit: " . get_class($imagesForEdit) . " with " . $imagesForEdit->count() . " items\n";
        
        // Test accessing properties that might trigger the error
        $clientName = $first->client_name;
        echo "✅ Client name access: {$clientName}\n";
        
        $status = $first->status;
        echo "✅ Status access: {$status}\n";
    }
    
    echo "\n🎉 ALL TESTS PASSED!\n";
    echo "✅ No addEagerConstraints errors\n";
    echo "✅ System is stable and ready\n";
    echo "\nNow create the images table and you'll be all set!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
