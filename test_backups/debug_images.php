<?php
// Debug inspection images
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Inspection;

echo "Debugging Inspection Images...\n\n";

// Get the latest inspection
$inspection = Inspection::latest()->first();

if (!$inspection) {
    echo "No inspections found in database.\n";
    exit;
}

echo "Inspection: {$inspection->inspection_number}\n";
echo "Raw inspection_images field: " . ($inspection->getRawOriginal('inspection_images') ?? 'NULL') . "\n";
echo "Processed inspection_images: " . json_encode($inspection->inspection_images) . "\n";
echo "Is array: " . (is_array($inspection->inspection_images) ? 'YES' : 'NO') . "\n";
echo "Count: " . (is_array($inspection->inspection_images) ? count($inspection->inspection_images) : 'N/A') . "\n";

if (is_array($inspection->inspection_images) && count($inspection->inspection_images) > 0) {
    echo "\nImage data structure:\n";
    foreach ($inspection->inspection_images as $index => $image) {
        echo "Image $index: " . json_encode($image, JSON_PRETTY_PRINT) . "\n";
    }
}

echo "\nDone.\n";
?>
