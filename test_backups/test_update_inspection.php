<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing inspection update functionality...\n";
    
    // Find inspection ID 47
    $inspection = App\Models\Inspection::find(47);
    
    if (!$inspection) {
        echo "❌ Inspection with ID 47 not found\n";
        
        // List available inspections
        $inspections = App\Models\Inspection::select('id', 'inspection_number', 'client_name')->take(5)->get();
        echo "Available inspections:\n";
        foreach ($inspections as $insp) {
            echo "  ID: {$insp->id} - {$insp->inspection_number} - {$insp->client_name}\n";
        }
        exit(1);
    }
    
    echo "✓ Found inspection: {$inspection->inspection_number} - {$inspection->client_name}\n";
    echo "  Current status: {$inspection->status}\n";
    echo "  Last updated: {$inspection->updated_at}\n";
    
    // Test update
    $inspection->update([
        'general_notes' => 'Test update at ' . now()->format('Y-m-d H:i:s')
    ]);
    
    echo "✓ Update successful!\n";
    echo "  New timestamp: {$inspection->fresh()->updated_at}\n";
    
    // Test image relationship
    $imageCount = $inspection->images()->count();
    echo "✓ Images loaded: {$imageCount} images found\n";
    
    if ($imageCount > 0) {
        $firstImage = $inspection->images()->first();
        echo "  First image: {$firstImage->original_name}\n";
    }
    
    echo "\n✅ ALL TESTS PASSED!\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
