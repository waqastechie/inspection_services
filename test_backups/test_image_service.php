<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inspection;
use App\Services\ImageUploadService;

echo "=== Testing Image Upload Service ===\n";

// Get inspection 47
$inspection = Inspection::find(47);
if (!$inspection) {
    echo "Inspection 47 not found\n";
    exit;
}

echo "Found inspection: {$inspection->id}\n";

// Create test image data
$testImageData = [
    [
        'name' => 'Test Image',
        'dataUrl' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAGAHvf9MgAAAABJRU5ErkJggg==',
        'caption' => 'Test caption'
    ]
];

// Test the service
$service = new ImageUploadService();
echo "Testing storeImages...\n";

try {
    $result = $service->storeImages($inspection, $testImageData);
    echo "Result: " . json_encode($result) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "=== Test Complete ===\n";
