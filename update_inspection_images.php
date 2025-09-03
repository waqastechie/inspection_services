<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$inspection = \App\Models\Inspection::find(19);

if ($inspection) {
    echo "Current inspection images:\n";
    $currentImages = is_string($inspection->inspection_images) ? 
        $inspection->inspection_images : 
        json_encode($inspection->inspection_images);
    echo $currentImages . "\n\n";
    
    // Define comprehensive image set for crane inspection
    $images = [
        'images/inspections/crane-overview-001.jpg',
        'images/inspections/boom-assembly-002.jpg', 
        'images/inspections/load-test-003.jpg',
        'images/inspections/mpi-results-004.jpg',
        'images/inspections/defect-detail-005.jpg',
        'images/inspections/load_test_test_1_overview.jpg',
        'images/inspections/ndt_inspection_test_1_detail.jpg',
        'images/inspections/visual_inspection_test_1_overview.jpg'
    ];
    
    // Update inspection with comprehensive images
    $inspection->inspection_images = json_encode($images);
    $inspection->save();
    
    echo "Updated inspection images to:\n";
    foreach ($images as $image) {
        echo "- $image\n";
    }
    
    echo "\nImages count: " . count($images) . "\n";
    echo "\nVerifying update...\n";
    
    $updated = \App\Models\Inspection::find(19);
    $updatedImages = is_string($updated->inspection_images) ? 
        json_decode($updated->inspection_images, true) : 
        $updated->inspection_images;
    echo "Verified images count: " . count($updatedImages) . "\n";
    
} else {
    echo "Inspection #19 not found\n";
}
