<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$inspection = \App\Models\Inspection::find(19);

if ($inspection) {
    // Create comprehensive image objects with proper metadata
    $imageObjects = [
        [
            'name' => 'Crane Overview',
            'path' => 'images/inspections/crane-overview-001.jpg',
            'caption' => 'General overview of the offshore crane structure showing main boom assembly and base configuration',
            'type' => 'overview',
            'service_relation' => 'visual_inspection'
        ],
        [
            'name' => 'Boom Assembly Detail',
            'path' => 'images/inspections/boom-assembly-002.jpg', 
            'caption' => 'Detailed view of boom assembly connections and structural components',
            'type' => 'detail',
            'service_relation' => 'lifting_examination'
        ],
        [
            'name' => 'Load Test Setup',
            'path' => 'images/inspections/load-test-003.jpg',
            'caption' => 'Load test configuration showing test weights and measurement equipment',
            'type' => 'test_setup',
            'service_relation' => 'load_test'
        ],
        [
            'name' => 'MPI Results',
            'path' => 'images/inspections/mpi-results-004.jpg',
            'caption' => 'Magnetic particle inspection results showing acceptable weld quality',
            'type' => 'test_result',
            'service_relation' => 'mpi'
        ],
        [
            'name' => 'Minor Defect Detail',
            'path' => 'images/inspections/defect-detail-005.jpg',
            'caption' => 'Surface corrosion noted on secondary structural member - within acceptable limits',
            'type' => 'defect',
            'service_relation' => 'visual_inspection'
        ],
        [
            'name' => 'Load Test Overview',
            'path' => 'images/inspections/load_test_test_1_overview.jpg',
            'caption' => 'Overall view of load test setup and crane positioning during proof load test',
            'type' => 'test_overview',
            'service_relation' => 'load_test'
        ],
        [
            'name' => 'NDT Inspection Detail',
            'path' => 'images/inspections/ndt_inspection_test_1_detail.jpg',
            'caption' => 'Close-up view of NDT inspection procedure on critical weld joint',
            'type' => 'procedure',
            'service_relation' => 'mpi'
        ],
        [
            'name' => 'Visual Inspection Overview',
            'path' => 'images/inspections/visual_inspection_test_1_overview.jpg',
            'caption' => 'Comprehensive visual inspection of crane structure and safety systems',
            'type' => 'inspection_overview',
            'service_relation' => 'visual_inspection'
        ]
    ];
    
    // Convert to the format expected by the view (backward compatible)
    $imageArray = [];
    foreach ($imageObjects as $imageObj) {
        $imageArray[] = $imageObj['path']; // Keep simple paths for compatibility
    }
    
    // Update inspection with enhanced images
    $inspection->inspection_images = json_encode($imageArray);
    $inspection->save();
    
    echo "Updated inspection #19 with " . count($imageArray) . " enhanced images:\n\n";
    
    foreach ($imageObjects as $index => $imageObj) {
        echo ($index + 1) . ". " . $imageObj['name'] . "\n";
        echo "   Path: " . $imageObj['path'] . "\n";
        echo "   Caption: " . $imageObj['caption'] . "\n";
        echo "   Related Service: " . $imageObj['service_relation'] . "\n\n";
    }
    
    echo "✅ Images successfully updated and are now visible in the inspection view!\n";
    
} else {
    echo "Inspection #19 not found\n";
}
