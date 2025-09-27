<?php
require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\InspectionController;
use App\Models\Inspection;

echo "Testing Equipment Debugging System\n";
echo "================================\n\n";

// Check if inspection 5 exists
$inspection = Inspection::find(5);
if ($inspection) {
    echo "✓ Inspection 5 exists: {$inspection->inspection_no} - {$inspection->client_name}\n";
    echo "  Status: {$inspection->current_step}\n";
    
    // Count current equipment
    $currentEquipment = $inspection->equipment()->count();
    echo "  Current equipment count: {$currentEquipment}\n\n";
    
    // Simulate equipment data submission
    echo "Simulating equipment data submission...\n";
    
    $testEquipmentData = [
        [
            'id' => '',
            'brand_manufacturer' => 'Test Brand',
            'quantity_available' => '5',
            'unit' => 'pcs',
            'is_active' => '1'
        ],
        [
            'id' => '',
            'brand_manufacturer' => 'Another Brand',
            'quantity_available' => '3',
            'unit' => 'sets',
            'is_active' => '1'
        ]
    ];
    
    echo "Test equipment data:\n";
    echo json_encode($testEquipmentData, JSON_PRETTY_PRINT) . "\n\n";
    
    // Create mock request
    $mockRequest = new Request();
    $mockRequest->merge([
        'step' => 4,
        'equipment_data' => json_encode($testEquipmentData),
        'inspection_id' => 5
    ]);
    
    echo "Mock request data:\n";
    echo "- step: " . $mockRequest->get('step') . "\n";
    echo "- inspection_id: " . $mockRequest->get('inspection_id') . "\n";
    echo "- equipment_data length: " . strlen($mockRequest->get('equipment_data')) . " chars\n\n";
    
    // Test the controller method directly
    try {
        $controller = new InspectionController();
        $response = $controller->saveWizardStep($mockRequest);
        
        echo "Controller response status: " . $response->getStatusCode() . "\n";
        echo "Response content: " . $response->getContent() . "\n\n";
        
        // Check if equipment was saved
        $newEquipment = $inspection->fresh()->equipment()->count();
        echo "Equipment count after save: {$newEquipment}\n";
        
        if ($newEquipment > $currentEquipment) {
            echo "✓ SUCCESS: Equipment was saved!\n";
        } else {
            echo "✗ FAILED: No equipment was saved\n";
        }
        
    } catch (Exception $e) {
        echo "✗ ERROR: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
    
} else {
    echo "✗ Inspection 5 not found!\n";
}

echo "\n=== Test Complete ===\n";
?>